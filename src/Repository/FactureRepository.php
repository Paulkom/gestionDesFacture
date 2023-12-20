<?php

namespace App\Repository;

use App\Entity\Facture;
use App\Services\LibrairieService;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    private $librairieService;
    
    public function __construct(ManagerRegistry $registry, LibrairieService $librairieService)
    {
        $this->librairieService=$librairieService;
        parent::__construct($registry, Facture::class);
    }


    
    public function add(Facture $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Facture $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function factureConversation($user){
        $query =  $this->createQueryBuilder('f')
            ->andWhere('f.estSup = :sup')
            ->setParameter("sup",0);
            //dd( $user->isEstAdmin());

            if(!$user->isEstAdmin())
            {
                $query->andWhere('f.acteur = :val')
                ->setParameter('val', $user->getId());
            }
            $query->andWhere('f.estValide = :val2 ')
                ->setParameter('val2', 0);
            $query->andWhere('f.montantRest != :val3 ')
                ->setParameter('val3', 0);
            $query->orderBy('f.id', 'DESC');
            ;
       return $query->getQuery()->getResult();
        
    }

    // public function pointJounalierVentes($start,$point, $length, $orders, $search,$date1,$date2)
    // {
    //     // Create Main Query
    //     $query = $this->createQueryBuilder('c')
    //     ->select("c.refCom,c.dateCom,
    //     CONCAT(CASE WHEN cc.nom IS NULL THEN '' ELSE cc.nom END,' ',CASE WHEN cc.prenom IS NULL THEN '' ELSE cc.prenom END,' ',CASE WHEN cc.denomination IS NULL THEN '' ELSE cc.denomination END) as client,
    //      c.montantHt,c.montantTtc,c.typeCommande,c.statut");
 
    //     // Create Count Query
    //     $countQuery = $this->createQueryBuilder('c');
    //     $countQuery->select('COUNT(c)');
        
    //     // Create inner joins
    //     $countQuery->leftJoin('c.acheteur', 'cc')->innerJoin("c.pointVente",'pv')->andWhere('c.deletedAt IS NULL');
    //     $query->leftJoin('c.acheteur', 'cc')->innerJoin("c.pointVente",'pv')->andWhere('c.deletedAt IS NULL');
 
        
    //     if($point){
    //          $query->andWhere('pv.id = :id')
    //          ->setParameter('id', $point);
    //          $countQuery->andWhere('pv.id = :id')
    //          ->setParameter('id', $point);  
    //      }
    //      if ($date1) {
    //          $query->andWhere('c.dateCom >= :date1')
    //          ->setParameter('date1', $date1);
    //          $countQuery->andWhere('c.dateCom >= :date1')
    //          ->setParameter('date1', $date1);
    //      }
    //      if ($date2){
    //          $query->andWhere('c.dateCom <= :date2')
    //          ->setParameter('date2', $date2);
    //          $countQuery->andWhere('c.dateCom <= :date2')
    //          ->setParameter('date2', $date2);
    //      }
        
    //      if($search != null && $search["value"] != ''){
    //          if (is_array($search))
    //              $searchItem = trim($search["value"]);
    //          else
    //              $searchItem = trim($search);
    //         $searchQuery = 'c.refCom LIKE \'%'.$searchItem.'%\' OR ';
    //         $searchQuery .= 'c.dateCom LIKE \'%'.$searchItem.'%\' OR ';
    //         $searchQuery .= 'cc.prenom LIKE \'%'.$searchItem.'%\' OR ';
    //         $searchQuery .= 'cc.nom LIKE \'%'.$searchItem.'%\' OR ';
    //         $searchQuery .= 'cc.raisonSociale LIKE \'%'.$searchItem.'%\' OR ';
    //         $searchQuery .= 'cc.sigle LIKE \'%'.$searchItem.'%\' OR ';
    //         $searchQuery .= 'c.montantHt LIKE \'%'.$searchItem.'%\' OR ';
    //         $searchQuery .= 'c.montantTtc LIKE \'%'.$searchItem.'%\' OR ';
    //         $searchQuery .= 'c.typeCommande LIKE \'%'.$searchItem.'%\' OR ';
    //         $searchQuery .= 'c.statut LIKE \'%'.$searchItem.'%\'';
           
    //         if($searchQuery !== null){
    //             $query->andWhere($searchQuery);
    //             $countQuery->andWhere($searchQuery);
    //         }
    //      }
    //      // Limit
    //      if ($start != '') {
    //          $query->setFirstResult($start)->setMaxResults($length);
    //      }
        
    //      // Order
    //      if ($orders!= null) {
    //          foreach($orders as $key => $order)
    //          {
    //              // $order['name'] is the name of the order column as sent by the JS
    //              if ($order['name'] != '')
    //              {
    //                  $orderColumn = null;
                 
    //                  switch($order['name'])
    //                  {
    //                      case 'refCom':
    //                      {
    //                          $orderColumn = 'c.refCom';
    //                          break;
    //                      }
                         
    //                      case 'dateCom':
    //                      {
    //                          $orderColumn = 'c.dateCom';
    //                          break;
    //                      }
    //                      case 'client':
    //                      {
    //                          $orderColumn = "CONCAT(CASE WHEN cc.nom IS NULL THEN '' ELSE cc.nom END,' ',CASE WHEN cc.prenom IS NULL THEN '' ELSE cc.prenom END,' ',CASE WHEN cc.raisonSociale IS NULL THEN '' ELSE cc.raisonSociale END,' ',CASE WHEN cc.denomination IS NULL THEN '' ELSE cc.denomination END)";
    //                          break;
    //                      }
    //                      case 'montantTtc':
    //                      {
    //                          $orderColumn = 'c.montantTtc';
    //                          break;
    //                      }
    //                      case 'montantHt':
    //                      {
    //                          $orderColumn = 'c.montantHt';
    //                          break;
    //                      }
    //                      case 'typeCommande':
    //                      {
    //                          $orderColumn = 'c.typeCommande';
    //                          break;
    //                      }
    //                      case 'statut':
    //                      {
    //                          $orderColumn = 'c.statut';
    //                          break;
    //                      }
                         
    //                  }
             
    //                  if ($orderColumn !== null)
    //                  {
    //                      $query->orderBy($orderColumn, $order['dir']);
    //                  }
    //              }
    //          }
    //      }
        
    //     // Execute
    //     if ($search != null && $start != null && $length != null) {
    //         $results = $this->librairieService->reformats($query->getQuery()->getArrayResult());
    //     }else {
    //         $results = $query->getQuery()->getResult();
    //     }
       
    //     $countResult = $countQuery->getQuery()->getSingleScalarResult();
    //   //dd($results);
    //     return array(
    //         "results" 		=>$results ,
    //         "countResult"	=> $countResult
    //     );  
    // } 
 
     
 
    
     //  public function pointJounalierVentesImp($search,$point,$date1,$date2)
    //  {
    //      // Create Main Query
    //      $query = $this->createQueryBuilder('c')
    //      ->select("c.refCom,c.dateCom,
    //      CONCAT(CASE WHEN cc.nom IS NULL THEN '' ELSE cc.nom END,' ',CASE WHEN cc.prenom IS NULL THEN '' ELSE cc.prenom END,' ',CASE WHEN cc.denomination IS NULL THEN '' ELSE cc.denomination END) as client,
    //       c.montantHt,c.montantTtc,c.typeCommande,c.statut");
 
    //      $query->leftJoin('c.acheteur', 'cc')->innerJoin("c.pointVente","pv")->andWhere('c.deletedAt IS NULL');
         
    //      if ($date1) {
    //          $query->andWhere('c.dateCom >= :date1')
    //          ->setParameter('date1', $date1);
    //      }
    //      if ($date2){
    //          $query->andWhere('c.dateCom <= :date2')
    //          ->setParameter('date2', $date2);
    //      }
    //      if($point){
    //          $query->andWhere('pv.id = :id')
    //          ->setParameter('id', $point); 
    //      }
         
    //       if($search != null && $search != ''){
    //          $searchItem = trim($search);
    //          $searchQuery = 'c.refCom LIKE \'%'.$searchItem.'%\' OR ';
    //          $searchQuery .= 'c.dateCom LIKE \'%'.$searchItem.'%\' OR ';
    //          $searchQuery .= 'cc.prenom LIKE \'%'.$searchItem.'%\' OR ';
    //          $searchQuery .= 'cc.nom LIKE \'%'.$searchItem.'%\' OR ';
    //          $searchQuery .= 'cc.raisonSociale LIKE \'%'.$searchItem.'%\' OR ';
    //          $searchQuery .= 'cc.sigle LIKE \'%'.$searchItem.'%\' OR ';
    //          $searchQuery .= 'c.montantHt LIKE \'%'.$searchItem.'%\' OR ';
    //          $searchQuery .= 'c.montantTtc LIKE \'%'.$searchItem.'%\' OR ';
    //          $searchQuery .= 'c.typeCommande LIKE \'%'.$searchItem.'%\' OR ';
    //          $searchQuery .= 'c.statut LIKE \'%'.$searchItem.'%\'';
            
    //          if($searchQuery !== null){
    //              $query->andWhere($searchQuery);
    //          }
    //       }
    //      return $query->getQuery()->getResult();
    //  } 
  
    public function counts()
    {
        $result = $this->createQueryBuilder('f')->select("count(f.id)")->andWhere('f.estSup != 1');
         return   $result->getQuery()->getSingleScalarResult();
    }
    
     public function pointDesFactures($start, $length, $orders, $search,$date1,$date2)
     {
        // Create Main Query
        $query = $this->createQueryBuilder('f')
        ->select("f.refFact,f.createdAt as dateFact ,f.emetteur,
         f.montantFac,f.montantRest,f.statut");
 
        // Create Count Query
        $countQuery = $this->createQueryBuilder('f');
        $countQuery->select('COUNT(f)');
        
        // Create inner joins
        // $countQuery->leftJoin('f.paiement', 'p');
        // $query->leftJoin('f.paiement', 'p');
        
       
         if ($date1) {
             $query->andWhere('f.createdAt >= :date1')
             ->setParameter('date1', $date1);
             $countQuery->andWhere('f.createdAt >= :date1')
             ->setParameter('date1', $date1);
         }
         if ($date2){
            $query->andWhere('f.createdAt <= :date2')
            ->setParameter('date2', $date2);
            $countQuery->andWhere('f.createdAt <= :date2')
            ->setParameter('date2', $date2);
         }
 
        //  if($statut){
        //      $query->andWhere('c.statut = :statut')
        //      ->setParameter('statut', $statut);
        //      $countQuery->andWhere('c.statut = :statut')
        //      ->setParameter('statut', $statut);
        //  }
 
        if($search != null){
             if (is_array($search))
                 $searchItem = trim($search["value"]);
             else
              $searchItem = trim($search);
 
             $searchQuery = 'f.refFact LIKE \'%'.$searchItem.'%\' OR ';
             $searchQuery .= 'f.dateFac LIKE \'%'.$searchItem.'%\' OR ';
             $searchQuery .= 'f.emetteur LIKE \'%'.$searchItem.'%\' OR ';
             $searchQuery .= 'f.montantFac LIKE \'%'.$searchItem.'%\' OR ';
             $searchQuery .= 'f.statut LIKE \'%'.$searchItem.'%\' OR ';
             $searchQuery .= 'f.montantRest LIKE \'%'.$searchItem.'%\'  ';
           
             if($searchQuery !== null){
                $query->andWhere($searchQuery);
                $countQuery->andWhere($searchQuery);
             }
        }
        
        
        // Order
        if ($orders!= null) {
             foreach($orders as $key => $order)
             {
                 // $order['name'] is the name of the order column as sent by the JS
                 if ($order['name'] != '')
                 {
                     $orderColumn = null;
                 
                     switch($order['name'])
                     {
                         case 'refCom':
                         {
                             $orderColumn = 'f.refCom';
                             break;
                         }
                         
                         case 'dateCom':
                         {
                             $orderColumn = 'f.dateFac';
                             break;
                         }
                         case 'emetteur':
                         {
                            $orderColumn = 'f.emetteur';
                            break;
                         }
                         case 'montantFac':
                         {
                             $orderColumn = 'f.montantFac';
                             break;
                         }
 
                         case 'montantRestant':
                         {
                             $orderColumn = 'f.montantRestant';
                             break;
                         }
 
 
                         case 'statut':
                         {
                             $orderColumn = 'f.statut';
                             break;
                         }
                         
                     }
             
                     if ($orderColumn !== null)
                     {
                         $query->orderBy($orderColumn, $order['dir']);
                     }
                 }
             }
        }
        // Limit
        if ($start != '') {
             $query->setFirstResult((int)$start)->setMaxResults((int)$length);
         }
        // Execute
        if ($start != null && $length != null) {
            $results = $this->librairieService->reformats($query->getQuery()->getArrayResult());
        }else {
            $results = $query->getQuery()->getResult();
        }
       
        $countResult = $countQuery->getQuery()->getSingleScalarResult();
      //dd($results);
        return array(
            "results" 		=>$results ,
            "countResult"	=> $countResult
        );  
 
    } 

    public function pointDesFacturesImprime($search,$date1,$date2)
    {
       // Create Main Query
       $query = $this->createQueryBuilder('f')
       ->select("f.refFact,f.dateFac,f.emetteur,
        f.montantFac,f.montantRest,f.statut");
       
       // Create inner joins
       //$query->leftJoin('f.paiement', 'p');
       
      
        if ($date1) {
            $query->andWhere('f.createdAt >= :date1')
            ->setParameter('date1', $date1);
        }
        if ($date2){
           $query->andWhere('f.createdAt <= :date2')
           ->setParameter('date2', $date2);
        }

       //  if($statut){
       //      $query->andWhere('c.statut = :statut')
       //      ->setParameter('statut', $statut);
       //      $countQuery->andWhere('c.statut = :statut')
       //      ->setParameter('statut', $statut);
       //  }

       if($search != null){
            if (is_array($search))
                $searchItem = trim($search["value"]);
            else
             $searchItem = trim($search);

            $searchQuery = 'c.refFact LIKE \'%'.$searchItem.'%\' OR ';
            $searchQuery .= 'c.dateFac LIKE \'%'.$searchItem.'%\' OR ';
            $searchQuery .= 'cc.emetteur LIKE \'%'.$searchItem.'%\' OR ';
            $searchQuery .= 'cc.montantFac LIKE \'%'.$searchItem.'%\' OR ';
            $searchQuery .= 'cc.statut LIKE \'%'.$searchItem.'%\' OR ';
            $searchQuery .= 'cc.montantRest LIKE \'%'.$searchItem.'%\'  ';
          
            if($searchQuery !== null){
               $query->andWhere($searchQuery);
            }
       }
       // Limit
       return  $query->getQuery()->getResult();

   } 

    public function countCom(){
        $year = date("Y");
        $date1 = new DateTime("1st January  $year");
        $date2 = new DateTime("1st December  $year");
        $date1->modify("first day of this month");
        $date2->modify("last day of this month");
        $start = $date1->format("Y-m-d");
        $end = $date2->format("Y-m-d");
        return $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->andWhere('f.dateFac BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
   }

//    /**
//     * @return Facture[] Returns an array of Facture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function dixDerniereFactures($user)
   {
       $query =  $this->createQueryBuilder('f');
       if(!$user->isEstAdmin()){
        $query->andWhere('f.acteur = :val')
        ->setParameter('val', $user->getId());
       }
       return $query->orderBy('f.id', 'DESC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
        
   }

   public function sommefactures($user)
   {
       $query =  $this->createQueryBuilder('f')
       ->select("SUM(f.montantFac)");
       if(!$user->isEstAdmin()){
        $query->andWhere('f.acteur = :val')
        ->setParameter('val', $user->getId());
       }
       return $query->orderBy('f.id', 'DESC')
           ->getQuery()
           ->getSingleResult()
       ;  
   }

   public function sommeReteApayerfactures($user)
   {
       $query =  $this->createQueryBuilder('f')
       ->select("SUM(f.montantRest)");
       if(!$user->isEstAdmin()){
        $query->andWhere('f.acteur = :val')
        ->setParameter('val', $user->getId());
       }
       return $query->orderBy('f.id', 'DESC')
           ->getQuery()
           ->getSingleResult()
       ;  
   }

//    public function findOneBySomeField($value): ?Facture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
