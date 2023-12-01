<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\ProfilType;
use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin/profil')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'profil', methods:["GET","POST"])]
    public function index(ProfilRepository $profilRepository, Request $request): Response
    {
        $profil = new Profil();
        $profils = $profilRepository->findBy(['deletedAt' => NULL]);
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilRepository->add($profil);
            if($profil->getId() != null)
                return new JsonResponse("Enregistrement effectué avec succès.");
        }

        return $this->renderForm('profil/index.html.twig', [
            'profils' => $profils,
            'form' => $form,
        ]);
    }
}
