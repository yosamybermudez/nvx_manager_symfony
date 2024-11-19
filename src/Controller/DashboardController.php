<?php

namespace App\Controller;

use App\Entity\ContableAccount;
use App\Entity\Contact;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Entity\Item;
use App\Entity\Service;
use App\Entity\User;
use App\Repository\InvoiceRepository;
use App\Repository\JournalEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/', name: 'app_dashboard')]
    public function index(JournalEntryRepository $journalEntryRepository, InvoiceRepository $invoiceRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'statistics' => [
                //Earnings - Month
                'paid_current_month' => $journalEntryRepository->findCurrentMonthPaidAmount(),
                //Earnings - Month
                'paid_current_year' => $journalEntryRepository->findCurrentYearPaidAmount(),
                // Unpaid Invoices
                'unpaid' => $invoiceRepository->findUnpaidAmount(),
            ]
        ]);
    }
}
