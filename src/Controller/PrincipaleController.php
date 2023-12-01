<?php

namespace App\Controller;

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
    public function index(): Response
    {
        return $this->render('base.html.twig', [
           
        ]);
    }

    #[Route('/generer/menus', name: 'generation_menu')]
    public function generateMenus() : Response
    {
        dd($this->parameters->getMenus(true));
    }

    public function afficherMenus(MenuRepository $menuRepository, EntityManagerInterface $em)
    {
        // $user = $this->getUser();
        // $userRoles = $user->getRoles();
        
        // $em = $this->getDoctrine()->getManager();
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
