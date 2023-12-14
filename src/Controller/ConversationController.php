<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Facture;
use App\Form\CommentaireAjoutType;
use App\Repository\CommentaireRepository;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin/conversation')]
class ConversationController extends AbstractController
{
    #[Route('/', name: 'conversation')]
    public function index(FactureRepository $factureRepository, Request $request): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireAjoutType::class, $commentaire);
        $form->handleRequest($request);
        if($form->isSubmitted()){

        }
        $user = $this->getUser();
        $factures = $factureRepository->factureConversation($user);
        return $this->render('conversation/index.html.twig', [
            "factures"=>$factures,
        ]);
    }

    #[Route('/show/{id}', name: 'conversation_show', methods:["GET","POST"])]
    public function show(FactureRepository $factureRepository): Response
    {
        $user = $this->getUser();
        $factures = $factureRepository->factureConversation($user);
        return $this->render('conversation/index.html.twig', [
            "factures"=>$factures,
        ]);
    }

    #[Route('/get/conversation', name: 'conversation_get_commentaire', methods:["GET","POST"])]
    public function getConv(Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $id = $request->request->get('id');
        $commentaires = $commentaireRepository->commentairesDeFacture($id);
        return new JsonResponse($commentaires);
    }
}
