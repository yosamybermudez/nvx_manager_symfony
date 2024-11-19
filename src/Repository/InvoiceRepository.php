<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }


    public function findUnpaidClientInvoices($customerID){
        return $this->createQueryBuilder('i')
            ->where('i.invoiceStatus <> :status')
            ->andWhere('i.customer = :customerID')
            ->setParameter('status', 'Paid')
            ->setParameter('customerID', $customerID)
            ->getQuery()
            ->getResult();
    }

    public function findUnpaidAmount(){

        $unpaidInvoices = $this->createQueryBuilder('i')
            ->where('i.invoiceStatus = :status')
            ->andWhere('i.quote = false')
            ->setParameter('status', 'Unpaid')
            ->getQuery()
            ->getResult();

        $unpaidAmount = 0;

        foreach ($unpaidInvoices as $unpaidInvoice){
            $unpaidAmount += $unpaidInvoice->getAmount();
        }

        return $unpaidAmount;
    }

    //    /**
    //     * @return Invoice[] Returns an array of Invoice objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Invoice
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
