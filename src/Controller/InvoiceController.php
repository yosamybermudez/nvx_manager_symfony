<?php

namespace App\Controller;

use App\Entity\ContableAccount;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Entity\JournalEntry;
use App\Entity\Payment;
use App\Entity\TransactionCategory;
use App\Form\InvoiceType;
use App\Form\PaymentType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/invoice')]
final class InvoiceController extends AbstractController
{
    #[Route(name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findBy(['quote' => false]),
        ]);
    }

    #[Route('/new', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, InvoiceRepository $invoiceRepository): Response
    {
        //Create Object
        $invoice = new Invoice();

        //Add New Item
        $invoiceItem = new InvoiceItem();
        $invoice->addInvoiceItem($invoiceItem);
        $invoice->setPaymentMethod('Zelle');
        $invoice->setPaymentAccount('713-494-0821');

        //It's Not A Quote, Assing Current Date
        $invoice->setQuote(false);
        $invoice->setQuoteStatus('Never A Quote');
        $invoice->setDocumentDate(new \DateTimeImmutable());

        //Assign Document Number
        $invoices = $invoiceRepository->findBy(['quote' => false]);
        $documentNumber = 'INV-' . substr(1000001 + count($invoices),1);
        $invoice->setDocumentNumber($documentNumber);

        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $currentDateTime = new \DateTime();
            $currentHour = $currentDateTime->format('H');
            $currentMinute = $currentDateTime->format('i');
            $currentSecond = $currentDateTime->format('s');
            $invoice->setDocumentDate($invoice->getDocumentDate()->setTime($currentHour, $currentMinute, $currentSecond));

            dd($invoice);

            //Update Invoice Amount
            $invoiceItems = $invoice->getInvoiceItems();
            $amount = 0;
            foreach ($invoiceItems as $invoiceItem){
                $amount += $invoiceItem->getAmount();
            }
            $invoice->setAmount($amount);
            $invoice->setPendingAmount($amount);
            //Persist
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice, InvoiceRepository $invoiceRepository): Response
    {


        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
            'invoices' => $invoiceRepository->findBy(['quote' => false])
        ]);
    }

    #[Route('/{uuid}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{uuid}/payment', name: 'app_invoice_payment', methods: ['GET', 'POST'])]
    public function payment(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        $generalDescription = sprintf('Pago - Cliente %s - Factura %s', $invoice->getCustomer()->getDisplayName(), $invoice->getDocumentNumber());
        $payment = new Payment();

        $payment->setInvoice($invoice);
        $payment->setAmount($invoice->getPendingAmount());
        $payment->setPaymentMethod('Zelle');
        $payment->setTransactionDate($invoice->getDocumentDate());
        $payment->setNotes($generalDescription);
        $payment->setCustomer($invoice->getCustomer());

        $zelleTony = $entityManager->getRepository(ContableAccount::class)->findOneBy(['name' => 'Zelle Tony']);
        $payment->setContableAccount($zelleTony);

        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        $form = $this->createForm(PaymentType::class, $payment, [
            'action' => $this->generateUrl('app_invoice_payment', ['uuid' => $invoice->getUuid()])
        ]);
        $form->remove('invoice');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                //Persist Payment
                $entityManager->persist($payment);

                // Invoice Update
                $invoice->substractPaidAmount($payment->getAmount());
                $invoice->updateInvoiceStatus();

                $entityManager->persist($invoice);

                //Contable Account Update
                $contableAccount = $payment->getContableAccount();
                $contableAccount->addAmount($payment->getAmount());

                $entityManager->persist($contableAccount);

                //Journal Entry Data
                $journalEntry = new JournalEntry();
                $journalEntry->setInvoice($invoice);
                $journalEntry->setPaymentMethod($payment->getPaymentMethod());
                $journalEntry->setContableAccount($payment->getContableAccount());
                $journalEntry->setBalance($contableAccount->getAmount());
                $journalEntry->setEntryDate($payment->getTransactionDate());
                $journalEntry->setCredit($payment->getAmount());
                $journalEntry->setCurrency('USD');
                $journalEntry->setDescription($generalDescription);
                $journalEntry->setContact($invoice->getCustomer());

                $transactionCategory = $entityManager->getRepository(TransactionCategory::class)->find('1');
                $journalEntry->setTransactionCategory($transactionCategory);

                $entityManager->persist($journalEntry);

                $entityManager->flush();
            } catch (\Exception $e){
                return $this->render('invoice/turbo/_payment_error.html.twig', [
                    'message' => $e->getMessage(),
                ]);
            }

            //Save All
           //

            return $this->render('invoice/turbo/_payment_success.html.twig', [
                'payment' => $payment,
                'invoice' => $invoice
            ]);
            //return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invoice/turbo/_payment_form.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }
}
