<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Facture;
use App\Form\Commentaire2AjoutType;
use App\Form\CommentaireAjoutType;
use App\Repository\CommentaireRepository;
use App\Repository\FactureRepository;
use App\Repository\UtilisateurRepository;
use App\Services\SmsEnvoie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/admin/conversation')]
class ConversationController extends AbstractController
{
    #[Route('/', name: 'conversation')]
    public function index(SmsEnvoie $sms, FactureRepository $factureRepository,CommentaireRepository $commentaireRepository,UtilisateurRepository $userresp, Request $request): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(Commentaire2AjoutType::class, $commentaire);
        $form->handleRequest($request);
        // if($form->isSubmitted()){
        //     $facture = $factureRepository->find((int)$form->get("facture")->getData());
        //     $commentaire->setFacture($facture);
        //     $commentaire->setExpediteur($this->getUser());
        //     $commentaire->setObjet("Message");
        //     $commentaire->setEstLue(false);
        //     $commentaire->setDestinataire($userresp->find((int)$facture->getActeur()));
        //     //dd($commentaire);
        //     $sms->sendSms("+22998428515","",$commentaire->getMessage());
        //     $commentaireRepository->add($commentaire);
        //     return $this->redirectToRoute("conversation", [], Response::HTTP_SEE_OTHER);
        // }
        $user = $this->getUser();
        $factures = $factureRepository->factureConversation($user);
        return $this->render('conversation/index.html.twig', [
            "factures"=>$factures,
            "form"=>$form
        ]);
    }

    #[Route('/message/envoie', name: 'conversation_message', methods:["GET","POST"])]
    public function message(SmsEnvoie $sms,FactureRepository $factureRepository,CommentaireRepository $commentaireRepository, Request $request,UtilisateurRepository $userresp): Response
    {
        if($request->isMethod("POST")){
            $commentaire = new Commentaire();
            $id = $request->request->get('facture');
            $message = $request->request->get('message');
            $facture = $factureRepository->find((int)$id);
            $admin = $userresp->findOneBy(["estAdmin"=>1, "estSup"=>0]);
            $commentaire->setObjet('Message');
            $commentaire->setMessage($message);
            $commentaire->setFacture($facture);
            $commentaire->setEstLue(false);
            $commentaire->setExpediteur($this->getUser());
            if($this->getUser()->isEstAdmin()){
                $commentaire->setDestinataire($admin);
            }
            // $commentaire->setDestinataire($userresp->find((int)$facture->getActeur()));
            // $commentaire->setExpediteur($this->getUser());
            $commentaireRepository->add($commentaire);
            $sms->sendSms("+22998428515","",$message);
            return new JsonResponse("Ok");
        }
        return new JsonResponse("Erreur");
        
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
