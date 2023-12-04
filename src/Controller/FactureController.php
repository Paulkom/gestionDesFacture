<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\CommentaireRepository;
use App\Repository\FactureRepository;
use App\Repository\ModeleRepository;
use App\Repository\SocieteRepository;
use App\Repository\UserRepository;
use App\Services\ApiMecef;
use App\Services\LibrairieService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use NumberFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/admin/facture')]
class FactureController extends AbstractController
{

    #[Route('/', name: 'app_Facture_index', methods: ['GET', 'POST'])]
    public function index(Request $request,MailerInterface $mailer,EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository, SocieteRepository $societeRepository, FactureRepository $factureRepository): Response
    {
        $facture = new Facture();
        
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            
            $facture->setEstSup(0);
            // $facture->setStatut("Facture");
            $factureRepository->add($facture);
            $commentaire = new Commentaire();
            $commentaire->setFacture($facture);
            $commentaire->setObjet("Nouvelle Facture émise");
            $commentaire->setMessage("Veuillez recevoir, Mme/Mr la personne chargée de l'étude des factures, la facture suivante pour évaluation. Merci");
            $commentaireRepository->add($commentaire);

// Envoie de mail
            $email = (new Email())
            ->from('paulk379@gmail.com')
            ->to('paulkombieni12@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Edition d\'une nouvelle facture n° '.$facture->getRefFact())
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
            $mailer->send($email);
/// Fin de l'envoie du mail
            if($facture->getId() != null){
                $id = $facture->getId();
                $statu = $form->get("truc")->getData();
                $facture = new Facture();

                $form = $this->createForm(FactureType::class, $facture);
                $html = $this->renderView('facture/partials/_card_form.html.twig', ['commande' => $facture, 'form' => $form->createView() ]);
                $comp ="§".$id."§".$statu."§".$html;
                return new JsonResponse("La facture a été créée avec succès. ". $comp);
            }
        }
        
        return $this->renderForm('facture/index.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/impression/docu', name: 'imprime_commande', methods: ['GET', 'POST'])]
    public function impression(LibrairieService $library, SocieteRepository $sociRep, Request $request, FactureRepository $factureCliRe){
        $id = $request->request->get('id');
        //$estCommande = $request->request->get('commande');
        $type = ((!empty($request->request->get('type')))) ? $request->request->get('type') : "" ; 
        $format = "A4-P";
        $ent = null;

        if($this->getUser()){
            $ent = ($sociRep->find($this->getUser()));
        }
        
        //dd($ent,$signataires);
        $facture = $factureCliRe->find($id);
        
        $htmlPage = $this->renderView("Facture/document/factureA4.html.twig",[
            'facture'=>$facture,
            'format'=>$format,
            'societe'=>$ent,
            "qr"=> null,
            'facture' => $facture,
            'valeur'=> $library->getFormattedNumber(value:$facture->getMontantFac(),  style: NumberFormatter::SPELLOUT)
        ]);


        
        return $library->mpdf([$htmlPage,$htmlPage],"Facture",$format);
        // if($type !== ""){
        //     return $library->mpdf([$htmlPage,$htmlPage],"Facture",$format); 
        // }else{
        //     return $library->mpdf([$htmlPage,$htmlPage,$bonHtml],"Facture",$format);
        // }
        
    }



    #[Route('/verifie/statut', name: 'app_commande_verifie_statut', methods: ['POST','GET'])]
    public function verifie_statut(Request $request, FactureRepository $factureClientRepository): Response
    {
        $code = $request->query->get('code');
        $motif = $request->query->get('motif');
       $statut = $factureClientRepository->findOneBy(['refCmd'=>$code,'statut'=>'Annuler']);
       if ($statut) {
            return new JsonResponse(['estannuler'=>false,'motif'=>$motif]);
       }else{
            return new JsonResponse(['estannuler'=>true,'motif'=>$motif]);
       }
    }


    #[Route('/new', name: 'app_Facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_Facture_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('facture/new.html.twig', [
            'commande' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_Facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('Facture/show.html.twig', [
            'commande' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_Facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_Facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Facture/edit.html.twig', [
            'commande' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_Facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_Facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
