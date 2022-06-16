<?php

namespace App\Repository;

use App\Entity\GroupMail;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupMail>
 *
 * @method GroupMail|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupMail|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupMail[]    findAll()
 * @method GroupMail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupMailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupMail::class);
    }

    public function add(GroupMail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GroupMail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return GroupMail[] Returns an array of GroupMail objects
     */
    public function findByUser($user): array
    {
        $query = $this->createQueryBuilder('g');
        $query->join("g.users",'u')
            ->where('u.id ='.$user->getId())
            ;

        return $query->getQuery()->getResult();

    }

    /**
     * @return GroupMail[] Returns an array of GroupMail objects
     */
    public function findByEvent($event): array
    {
        $query = $this->createQueryBuilder('g');
        $query->join("g.events",'e')
            ->where('e.id ='.$event->getId())
        ;

        return $query->getQuery()->getResult();

    }

//    public function findOneBySomeField($value): ?GroupMail
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
