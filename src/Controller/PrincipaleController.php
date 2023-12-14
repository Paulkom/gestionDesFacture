<?php

namespace App\Controller;

use App\Repository\FactureRepository;
use App\Repository\MenuRepository;
use App\Services\LibrairieService;
use App\Services\Parameters;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PrincipaleController extends AbstractController
{
    private $parameters;
    protected $em;
    protected $library;
    private $menuRepo;
    private $profilRepo;
    public function __construct(Parameters  $parameters,LibrairieService $librairieService, MenuRepository $menuRepository, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasherInterface){
        $this->parameters = $parameters;
        $this->em = $em;
        $this->menuRepo = $menuRepository;
        $this->library = $librairieService;
    }

    #[Route('/admin', name: 'principal')]
    public function index(FactureRepository $factureRepository): Response
    {
        $user = $this->getUser();
        $fatures =  $factureRepository->dixDerniereFactures($user);
        $sommeFacture = $factureRepository->sommefactures($user);
        $sommeReste = $factureRepository->sommeReteApayerfactures($user);
       $paie = ((float)$sommeFacture[1] -(float)$sommeReste[1]);
        return $this->render('base.html.twig', [
            'factures' => $fatures,
            "sommeFacture"=>(float)$sommeFacture[1] == 0 ? 0 : $sommeFacture[1],
            "sommeReste"=>(float)$sommeReste[1] == 0 ? 0 :$sommeReste[1],
            "payer"=> $paie,
            "pourcentage"=> (float)$sommeFacture[1] ==0 ? 0 : round((((float)$paie / (float)$sommeFacture[1]) * 100),2),
        ]);
    }

    #[Route('/generer/menus', name: 'generation_menu')]
    public function generateMenus() : Response
    {
        dd($this->parameters->getMenus(true));
    }

    public function afficherMenus(MenuRepository $menuRepository, EntityManagerInterface $em)
    {
        $menus = $em->createQuery("
            SELECT m
            FROM App\Entity\Menu m
            LEFT JOIN m.menuSuperieur ms
            LEFT JOIN m.sousMenus sm
            WHERE ms IS NULL
            "
        )
        // LEFT JOIN m.sousMenus sm AND sm.roles IN (:val)
        // ->setParameter('val',$userRoles)
        ->getResult();
        return $this->render('layouts/menu.html.twig', compact('menus'));
    }
}
