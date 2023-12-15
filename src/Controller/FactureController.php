<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Facture;
use App\Entity\Paiement;
use App\Form\CommentaireAjoutType;
use App\Form\FactureType;
use App\Repository\CommentaireRepository;
use App\Repository\FactureRepository;
use App\Repository\ModeleRepository;
use App\Repository\SocieteRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurRepository;
use App\Services\ApiMecef;
use App\Services\LibrairieService;
use App\Services\SmsEnvoie;
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

    #[Route('/', name: 'facture_index', methods: ['GET', 'POST'])]
    public function index(Request $request,UtilisateurRepository $userresp, MailerInterface $mailer,EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository, SocieteRepository $societeRepository, FactureRepository $factureRepository): Response
    {
        $facture = new Facture();
        
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            
            $admin = $userresp->findOneBy(["estAdmin"=>1, "estSup"=>0]);
            $facture->setEstSup(0);
            $facture->setMontantRest($facture->getMontantFac());
            $facture->setActeur(($this->getUser())->getId());
            $factureRepository->add($facture);
            $commentaire = new Commentaire();
            $commentaire->setFacture($facture);
            $commentaire->setObjet("Nouvelle Facture émise");
            $commentaire->setExpediteur($this->getUser());
            if(!$this->getUser()->isEstAdmin()){
                $commentaire->setDestinataire($admin);
            }else{
                $commentaire->setDestinataire($factureRepository->find($facture->getActeur()));
            }
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
                $html = $this->renderView('facture/partials/_card_form.html.twig', ['facture' => $facture, 'form' => $form->createView() ]);
                $comp ="§".$id."§".$statu."§".$html;
                return new JsonResponse("La facture a été créée avec succès. ". $comp);
            }
        }
        
        return $this->renderForm('facture/index.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/impression/docu', name: 'imprime_facture', methods: ['GET', 'POST'])]
    public function impression(LibrairieService $library, SocieteRepository $sociRep, Request $request, FactureRepository $factureCliRe){
        $id = $request->request->get('id');
        $type = ((!empty($request->request->get('type')))) ? $request->request->get('type') : "" ; 
        $format = "A4-P";
        $ent = null;

        if($this->getUser()){
            $ent = ($sociRep->find($this->getUser()));
        }
        
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
        
    }

    #[Route('/{id}/show', name: 'facture_show',  methods: ['GET', 'POST'])]
    public function show2(Facture $facture, SmsEnvoie $sms, FactureRepository $factureRepository, Request $request, CommentaireRepository $commentaireRepository, UtilisateurRepository $userresp): Response
    {
        $commentaires = $commentaireRepository->troisDerniersCommentaireDeFacture($facture);
        if($this->getUser()->isEstAdmin() == true){
            $commentaire = new Commentaire();
            $form = $this->createForm(CommentaireAjoutType::class, $commentaire);
            $form->handleRequest($request);

            if($form->isSubmitted()){
                $admin = $userresp->findOneBy(["estAdmin"=>1, "estSup"=>0]);
                $commentaire->setFacture($facture);
                $commentaire->setExpediteur($this->getUser());
                $commentaire->setObjet("Réponse");
                $commentaire->setEstLue(false);
                $commentaire->setExpediteur($this->getUser());
                if(!$this->getUser()->isEstAdmin()){
                    $commentaire->setDestinataire($admin);
                }else{
                    $commentaire->setDestinataire($factureRepository->find($facture->getActeur()));
                }
                //dd($commentaire);
                $sms->sendSms("+22998428515","",$commentaire->getMessage());
                $commentaireRepository->add($commentaire);
                
                return $this->redirectToRoute("facture_index", [], Response::HTTP_SEE_OTHER);
            }
        }
        
        return $this->renderForm('facture/show.html.twig', [
            'entitie' => $facture,
           "commentaires"=> $commentaires,
            'form'=> $this->getUser()->isEstAdmin()? $form : null,
        ]);
    }



    #[Route('/verifie/statut', name: 'app_facture_verifie_statut', methods: ['POST','GET'])]
    public function verifie_statut(Request $request, FactureRepository $factureRepository): Response
    {
        $code = $request->query->get('code');
        $motif = $request->query->get('motif');
       $statut = $factureRepository->findOneBy(['refFact'=>$code])->getStatut();
       if ($statut ) {
            return new JsonResponse(['estSup'=>false,'motif'=>$motif]);
       }
       else
       {
            return new JsonResponse(['estSup'=>true,'motif'=>$motif]);
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
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_facture_show', methods: ['GET','POST'])]
    public function show(Facture $facture):Response
    {
        $results['html'] = $this->renderView('facture/show.html.twig', ['facture' => $facture,]);
        return new JsonResponse($results);
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
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/suppression/{key}/facture', name: 'app_annulationFacture', methods: ['GET', 'POST'])]
    public function annulationFacture($key,Request $request,EntityManagerInterface $em)
    {
        $motif=$request->query->get('motif');

        /** @var Facture */
        $facture = $em->getRepository(Facture::class)->find((int)$key);
        $paiements = $em->getRepository(Paiement::class)->paiementsFacture($facture);
        if(!empty($paiements)){
            return new JsonResponse("Erreur, Cette facture a déjà fait l'objet d'un paiement.");
        }else{
            if($facture->getId() != null){
                    $facture->setStatut('Supprimer');
                    $facture->setDeletedAt(new DateTime());
                    $facture->setEstSup(true);
                    $facture->setMotif($motif);
                    $em->getRepository(Facture::class)->add($facture);
                    return new JsonResponse("La facture ". $facture->getRefFact() ." a été annulée avec succès");
                
            } else{
                return new JsonResponse("Erreur, facture non trouvée!!!§§§§msg_error");       
            }
        }
    }

}
