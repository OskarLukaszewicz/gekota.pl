<?php

namespace App\Repository;

use App\Entity\AnimalTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnimalTable>
 *
 * @method AnimalTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalTable[]    findAll()
 * @method AnimalTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalTable::class);
    }

    public function add(AnimalTable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AnimalTable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
