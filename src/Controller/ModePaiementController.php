<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\ModePaiement;
use App\Form\ModePaiementType;
use App\Repository\ModePaiementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/mode/paiement')]
class ModePaiementController extends AbstractController
{
    // /**
    //  * @Route("/", name="app_mode_paiement_index", methods={"GET", "POST"})
    //  */
    #[Route('/', name: 'mode_paiement_index', methods:["GET","POST"])]
    public function index(Request $request, ModePaiementRepository $modePaiementRepository): Response
    {
        $modePaiement = new ModePaiement();
        $form = $this->createForm(ModePaiementType::class, $modePaiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modePaiementRepository->add($modePaiement);
            if($modePaiement->getId() != null)
                return new JsonResponse("Enregistrement effectué avec succès.");
        }

        return $this->renderForm('mode_paiement/index.html.twig', [
            'mode_paiements' => $modePaiementRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_depense_show', methods: ['GET'])]
    public function show2(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'entitie' => $facture,
        ]);
    }

    // /**
    //  * @Route("/{id}", name="app_", methods={"GET"})
    //  */
    #[Route('/{id}', name: 'mode_paiement_show', methods:["GET"])]
    public function show(ModePaiement $modePaiement): Response
    {
        return $this->render('mode_paiement/show.html.twig', [
            'mode_paiement' => $modePaiement,
        ]);
    }

    // /**
    //  * @Route("/{id}/edit", name="app_mode_paiement_edit", methods={"GET", "POST"})
    //  */
    #[Route('/{id}/edit', name: 'mode_paiement_edit', methods:["GET","POST"])]
    public function edit(Request $request, ModePaiement $modePaiement, ModePaiementRepository $modePaiementRepository): Response
    {
        $form = $this->createForm(ModePaiementType::class, $modePaiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modePaiementRepository->add($modePaiement);
            return $this->redirectToRoute('app_mode_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mode_paiement/edit.html.twig', [
            'mode_paiement' => $modePaiement,
            'form' => $form,
        ]);
    }

    // /**
    //  * @Route("/{id}", name="app_mode_paiement_delete", methods={"POST"})
    //  */
    #[Route('/{id}', name: 'mode_paiement_delete', methods:["POST"])]
    public function delete(Request $request, ModePaiement $modePaiement, ModePaiementRepository $modePaiementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modePaiement->getId(), $request->request->get('_token'))) {
            $modePaiementRepository->remove($modePaiement);
        }

        return $this->redirectToRoute('app_mode_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
