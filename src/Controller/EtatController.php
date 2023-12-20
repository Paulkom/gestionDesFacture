<?php

namespace App\Controller;

use App\Repository\FactureRepository;
use App\Services\LibrairieService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtatController extends AbstractController
{
    // #[Route('/etat', name: 'app_etat')]
    // public function index(): Response
    // {
    //     return $this->render('etat/index.html.twig', [
    //         'controller_name' => 'EtatController',
    //     ]);
    // }

    public function prodDispoPrint($page,$nom,$donne, $formate = "A4")
    {  
       return $this->renderView($page, [$nom => $donne]);
    }

    #[Route('/etat', name: 'app_etat', methods:["GET","POST"])]
    public function imprime(Request $request,FactureRepository $factureRepository,LibrairieService $librairieService, $type =null){
        $ent = null;
        $view=null;
        set_time_limit(-1);
        $type = $request->query->get("type");
        $date1 = $request->query->get("date1");
        $user  = $this->getUser();
        $date2 = $request->query->get("date2");
        $recherche = $request->query->get("recherche");
        $donnes = $request->query->get("donnees");
        $statut = $request->query->get("statut") == "undefined" ? "": $request->query->get("statut");
        $date1 = $date1 == "undefined" ? "": $date1;
        $date2 = $date2 == "undefined" ? "": $date2;
        $date2 = $date2 == "undefined" ? "": $date2;
        $recherche = $recherche == "undefined" ? "": $recherche;
        $format = "A4-P";

        if($date1 != "undefined" && $date1 != ""){
            $date1 = $date1." 00:00:00";
        }else{
            $date1 = new DateTime();
            $date1 = $date1->format('Y-m-d 00:00:00');
        }
        if($date2 && $date2 != "undefined" && $date2 != ""){
            $date2 = $date2." 23:59:59";
        }else{
            $date2 = new DateTime();
            $date2 = $date2->format('Y-m-d 23:59:59');
        }
        $title="Imprimer";
        if ($type == "Point des factures émises"){
            $view = $this->prodDispoPrint('etat/facture/imprime/etat-facture-imprime.html.twig','donnees', $factureRepository->pointDesFacturesImprime($recherche,$date1, $date2));
            $title =  "Point des factures émises";
            $format = "A4-L";
        }
        
        $librairieService->mpdf([$view],$title,$format);
    }

    #[Route('/etat/point/entree', name: 'app_etat_point_entree')]
    public function statistiques()
    {
        return $this->renderForm('etat/index.html.twig');
    }

    #[Route('/etat/point/facture', name: 'app_etat_facture', methods:["GET","POST"])]
    public function pointFactures(FactureRepository $factureRepository,Request $request)
    {
        $user  = $this->getUser();
        $draw = intval($request->get('draw'));
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search');
        $orders = $request->get('order');
        $columns = $request->get('columns');
        $date1 = $request->get('dateDebut');
        $date2 = $request->get('dateFin');
        foreach ($orders as $key => $order)
        {
            $orders[$key]['name'] = $columns[$order['column']]['data'];
        }

        if($date1){
            $date1 = $date1." 00:00:00";
        }else{
            $date1 = new DateTime();
            $date1 = $date1->format('Y-m-d 00:00:00');
        }

        if($date2){
            $date2 = $date2." 23:59:59";
        }else{
            $date2 = new DateTime();
            $date2 = $date2->format('Y-m-d 23:59:59');
        }
        
        $total_objects_count = $factureRepository->counts();
        $results = $factureRepository->pointDesFactures($start, $length, $orders, $search,$date1, $date2);
        $response = array( 
            'draw'=>$draw, 
            'recordsFiltered' => $results["countResult"], 
            'recordsTotal' => $total_objects_count,
            'data' => $results["results"],
        ); 
        return new JsonResponse($response);
    }
}
