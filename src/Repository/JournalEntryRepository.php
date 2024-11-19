<?php

namespace App\Repository;

use App\Entity\JournalEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JournalEntry>
 */
class JournalEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JournalEntry::class);
    }

    public function findCurrentMonthPaidAmount(){

        $startOfMonth = new \DateTime('first day of this month 00:00:00');
        $endOfMonth = new \DateTime('last day of this month 23:59:59');

        $incomes = $this->createQueryBuilder('i')
            ->where('i.credit is not null')
            ->andWhere('i.entryDate BETWEEN :start AND :end')
            ->setParameter('start', $startOfMonth)
            ->setParameter('end', $endOfMonth)
            ->getQuery()
            ->getResult();

        $incomeAmount = 0;

        foreach ($incomes as $income){
            $incomeAmount += $income->getCredit();
        }

        return $incomeAmount;
    }

    public function findCurrentYearPaidAmount(){

        $startOfYear = new \DateTime( '2024-01-01 00:00:00');
        $endOfYear = new \DateTime('2024-12-31 23:59:59');

        $incomes = $this->createQueryBuilder('i')
            ->where('i.credit is not null')
            ->andWhere('i.entryDate BETWEEN :start AND :end')
            ->setParameter('start', $startOfYear)
            ->setParameter('end', $endOfYear)
            ->getQuery()
            ->getResult();

        $incomeAmount = 0;

        foreach ($incomes as $income){
            $incomeAmount += $income->getCredit();
        }

        return $incomeAmount;
    }

    //    /**
    //     * @return JournalEntry[] Returns an array of JournalEntry objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?JournalEntry
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
