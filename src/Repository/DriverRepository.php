<?php

namespace App\Repository;

use App\Entity\Driver;
use App\Entity\RaceResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Driver>
 */
class DriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Driver::class);
    }

    /**
     * @return Driver[]
     */
    public function findAllWithResults(): array
    {
        return $this->createQueryBuilder('driver')
            ->leftJoin('driver.raceResults', 'raceResults')
            ->getQuery()
            ->getResult();
    }

}
