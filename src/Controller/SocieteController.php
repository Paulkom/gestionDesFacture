<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Societe;
use App\Form\SocieteType;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/societe')]
class SocieteController extends AbstractController
{
    #[Route('/', name: 'societe_index', methods: ['GET', 'POST'])]
    public function index(Request $request,SocieteRepository $societeRepository): Response
    {
        $societe = new Societe();
        $form = $this->createForm(SocieteType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($societeRepository->findOneBy(['ifu'=>$societe->getIfu(),'raisonSocial'=>$societe->getRaisonSocial()])){
                return new JsonResponse("Cette societe existe déjà ? Veuillez vérifier vos informations.");
            }else{
                $societeRepository->add($societe);
            }
            
            //dump($societe->getEntete());
            if($societe->getId() != null){
                return new JsonResponse("Enregistrement effectué avec succès.");
            }
            // else{
            //     dump($entete, $logo, $piedPage);
            //     return new JsonResponse("Test fait");
            // }
                
        }

        return $this->renderForm('societe/index.html.twig', [
            'societes' => $societeRepository->findAll(),
            'form' => $form,
        ]);
    }

    public function chargeedia($file){
        $media = new Media();
        $media->setNomMedia(md5(uniqid()))->setExtension($file->guessExtension());
        //$this->getDoctrine()->getRepository(Media::class)->add($media);
        //$entity->set.$en($media);
        return $media;
    }

    public function uploadfile($entity, $file, $en){
        $media = new Media();

        $nomFi = md5(uniqid());
        //dump($file);
        $file->tempFilename = $this->getParameter('upload_dir_societe').'/'.$nomFi. '.'. $file->guessExtension();
        $file->move(
            $this->getParameter('upload_dir_societe') ,
            $nomFi. '.'. $file->guessExtension()
        );
        
        $media->setNomMedia($nomFi)->setExtension($file->guessExtension());
        //$this->getDoctrine()->getRepository(Media::class)->add($media);
        $entity->set.$en($media);
        return $entity;
    }

    #[Route('/new', name: 'app_societe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $societe = new Societe();
        $form = $this->createForm(SocieteType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($societe);
            $entityManager->flush();

            return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('societe/new.html.twig', [
            'societe' => $societe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_societe_show', methods: ['GET'])]
    public function show(Societe $societe): Response
    {
        return $this->render('societe/show.html.twig', [
            'societe' => $societe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_societe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Societe $societe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocieteType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('societe/edit.html.twig', [
            'societe' => $societe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_societe_delete', methods: ['POST'])]
    public function delete(Request $request, Societe $societe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$societe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($societe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_societe_index', [], Response::HTTP_SEE_OTHER);
    }
}
