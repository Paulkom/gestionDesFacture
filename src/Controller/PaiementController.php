<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Repository\ModeleRepository;
use App\Repository\ModeleSignataireRepository;
use App\Repository\PaiementRepository;
use App\Repository\SocieteRepository;
use App\Services\LibrairieService;
use Doctrine\ORM\EntityManagerInterface;
use NumberFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/paiement')]
class PaiementController extends AbstractController
{
    #[Route('/', name: 'app_paiement_index', methods: ['GET', 'POST'])]
    public function index(Request $request,PaiementRepository $paiementRepository): Response
    {
        $paiement = new Paiement();
        
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);
        $error = false;
        if ($form->isSubmitted() && $form->isValid()) {
            if(!$error)
            {
                $paiement->getFacture()->setMontantRest($paiement->getRestAPayer());
                if((float)$paiement->getRestAPayer() != 0.00){
                    //$paiement->getCommande()->setStatut("Partielle");
                    $paiement->getFacture()->setStatut("Partielle");
                }else{
                    //$paiement->getCommande()->setStatut("Payée");
                    $paiement->getFacture()->setStatut("Payée");
                }
                $paiementRepository->add($paiement); 
                if($paiement->getId() != null){
                    $paiement2 = new Paiement();
                    $form2 = $this->createForm(PaiementType::class, $paiement2);
                    //$form->handleRequest($request);
                    $html = $this->renderView('paiement/partials/_card_form.html.twig', ['paiement' => $paiement2,'form' => $form2->createView() ]);
                    $comp ="§".$paiement->getId()."§".$form->get("truc")->getData()."§".$html;
                    return new JsonResponse("Enregistrement éffectué avec succès.".$comp);
                }   
            }
    }
        return $this->renderForm('paiement/index.html.twig', [
            // 'paiements' => $paiementRepository->findAll(),
             'paiement' => $paiement,
             'form' => $form,
             'error' => $error,
         ]);
    }

    #[Route('/new', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paiement = new Paiement();
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/doc/paiement/imprime', name: 'imprime_paiement_imprime_new', methods: ['GET', 'POST'])]
    public function imprime(Request $request, SocieteRepository $sociRep,  PaiementRepository $paieRep, LibrairieService $library){
        $id = $request->request->get('id');
        $format = "A4-P";
        $paiement = $paieRep->find($id);
        $ent = null;

        if($sociRep->findAll()){
            $ent = ($sociRep->findAll())[0];
        }
        $htmlPage = $this->renderView("paiement/document/recu.html.twig",[
            'paiement'=>$paiement,
            'format'=>$format,
            'societe'=>$ent,
            'valeur'=> $library->getFormattedNumber(value:$paiement->getMontantPaie(),  style: NumberFormatter::SPELLOUT)
        ]);
        return $library->mpdf([$htmlPage],"Recu",$format);
    }

    #[Route('/{id}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
