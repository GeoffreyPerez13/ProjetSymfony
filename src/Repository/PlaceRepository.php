<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Place>
 *
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }

    public function save(Place $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Place $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Place[] Returns an array of Place objects
     */
    public function findBySearchKeywords($value): array
    {
        // DQL avec queryBuilder
        return $this->createQueryBuilder('p')
            ->leftJoin('p.pictures', 'pi')
            ->where('p.name LIKE :val OR p.description LIKE :val OR pi.title LIKE :val')
            ->setParameter('val', "%$value%")
            ->getQuery()
            ->getResult();


        // DQL sans queryBuilder
        /* return $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Place p LEFT JOIN p.pictures pi WHERE p.name LIKE :val OR p.description LIKE :val OR pi.title LIKE :val')
        ->setParameter('val', "%$value%")
        ->getResult(); */

    }

    /**
     * Summary of findRandomPlaces
     * @param mixed $count
     * @return mixed
     */
    public function findRandomPlaces($count)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('RAND()')
            ->setMaxResults($count)
            ->getQuery();

        return $query->getResult();
    }

//    public function findOneBySomeField($value): ?Place
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
