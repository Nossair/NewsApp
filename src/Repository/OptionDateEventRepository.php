<?php

namespace App\Repository;

use App\Entity\OptionDateEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OptionDateEvent>
 *
 * @method OptionDateEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionDateEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionDateEvent[]    findAll()
 * @method OptionDateEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionDateEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionDateEvent::class);
    }

    public function add(OptionDateEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OptionDateEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeByEvent($event_id, bool $flush = false): void
    {
        $optionDateToDelete = $this->createQueryBuilder('o')
            ->where('o.event = '.$event_id)
            ->getQuery()
            ->getResult();
        foreach ($optionDateToDelete as $item){
            $this->getEntityManager()->remove($item);
        }


        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return OptionDateEvent[] Returns an array of OptionDateEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OptionDateEvent
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
