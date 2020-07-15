<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function searchCar($criteria)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.cities','city')
            ->where('c.model LIKE :model')
            ->setParameter('model', '%'.$criteria['model'].'%')
            ->andWhere('city.name = :cityName')
            ->setParameter('cityName', $criteria['city']->getName())
            ->andWhere('c.color = :color')
            ->setParameter('color',$criteria['color'])
            ->andWhere('c.carburent = :carburent')
            ->setParameter('carburent', $criteria['carburent'])
            ->andWhere('c.price > :minimumPrice')
            ->setParameter('minimumPrice', $criteria['minimumPrice'])
            ->andWhere('c.price < :maximumPrice')
            ->setParameter('maximumPrice', $criteria['maximumPrice'])
            ->getQuery()
            ->getResult()
        ;
    }

}
