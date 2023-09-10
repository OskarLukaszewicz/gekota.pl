<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getEventsByDateRange(int $numberOfWeeks): array
    {
        $maxDate = new \DateTime();
        $maxDate->setTimestamp(time() + $numberOfWeeks * 60 * 60 *24 *7);

        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('event')
            ->from(Event::class, 'event')
            ->where('DATE_DIFF(event.date, CURRENT_DATE()) < DATE_DIFF(:maxDate, CURRENT_DATE())')
            ->andWhere('DATE_DIFF(event.date, CURRENT_DATE()) > 0')
            ->setParameter('maxDate', $maxDate)
            ->orderBy('event.date', 'ASC');
            
        $query = $qb->getQuery();

        return $query->execute();
    }

}
