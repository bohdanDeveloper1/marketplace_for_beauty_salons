<?php

namespace App\Repository;

use App\Entity\Salon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Salon>
 *
 * @method Salon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salon[]    findAll()
 * @method Salon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salon::class);
    }

    public function save(Salon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Salon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSalonsBySearchText($searchText, $cityId)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select('s')
            ->from(Salon::class, 's')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('s.belongToCity', ':cityId'),
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('s.name', ':searchText'),
                        $queryBuilder->expr()->like('s.adress', ':searchText')
                    )
                )
            )
            ->setParameter('searchText', '%' . trim($searchText) . '%')
            ->setParameter('cityId', $cityId)
            ->getQuery();

        return $query->getResult();
    }

    public function findAllSalonsBySearchText($searchText)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $query = $queryBuilder
            ->select('s')
            ->from(Salon::class, 's')
            ->where(
                $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('s.name', ':searchText'),
                        $queryBuilder->expr()->like('s.adress', ':searchText'),
                )
            )
            ->setParameter('searchText', '%' . trim($searchText) . '%')
            ->getQuery();

        return $query->getResult();
    }


//    /**
//     * @return Salon[] Returns an array of Salon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Salon
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
