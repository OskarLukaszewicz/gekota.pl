<?php

namespace App\Repository;

use App\Entity\DashboardConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DashboardConfig>
 *
 * @method DashboardConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method DashboardConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method DashboardConfig[]    findAll()
 * @method DashboardConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DashboardConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DashboardConfig::class);
    }

    public function add(DashboardConfig $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DashboardConfig $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
