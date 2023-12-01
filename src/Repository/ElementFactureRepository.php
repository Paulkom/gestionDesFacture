<?php

namespace App\Repository;

use App\Entity\ElementFacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ElementFacture>
 *
 * @method ElementFacture|null find($id, $lockMode = null, $lockVersion = null)
 * @method ElementFacture|null findOneBy(array $criteria, array $orderBy = null)
 * @method ElementFacture[]    findAll()
 * @method ElementFacture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementFactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElementFacture::class);
    }

    public function add(ElementFacture $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(ElementFacture $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


//    /**
//     * @return ElementFacture[] Returns an array of ElementFacture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ElementFacture
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
