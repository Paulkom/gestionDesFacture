<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/utilisateur')]
class CollaborateurController extends AbstractController
{
    #[Route('/', name: 'collaborateur', methods:["GET","POST"])]
    public function indexR(Request $request, UserPasswordHasherInterface $hasher, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = new Utilisateur();

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword(
                $hasher->hashPassword(
                    $utilisateur,
                    $form->get('password')->getData()
                )
            );
            $utilisateurRepository->add($utilisateur);
            if($utilisateur->getId() != null)
                return new JsonResponse("Enregistrement effectué avec succès.");
        }

        return $this->renderForm('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findBy(array(),array(),null,1),
            'form' => $form,
            // 'formCompte' => $formCompte,
        ]);
    }

    #[Route('/{id}', name: 'collaborateur_edit', methods:["GET"])]
    public function show(Utilisateur $personnel): Response
    {
        $view = $this->renderView('personnel/partials/_card_show.html.twig', compact('personnel'));
        return new JsonResponse($view);
    }
}
