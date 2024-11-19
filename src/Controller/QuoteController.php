<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Entity\Quote;
use App\Form\QuoteType;
use App\Repository\InvoiceRepository;
use App\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quote')]
final class QuoteController extends AbstractController
{
    #[Route(name: 'app_quote_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        return $this->render('quote/index.html.twig', [
            'quotes' => $invoiceRepository->findBy(['quote' => true]),
        ]);
    }

    #[Route('/new', name: 'app_quote_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, InvoiceRepository $invoiceRepository): Response
    {
        //Create Object
        $quote = new Invoice();

        //Add New Item
        $invoiceItem = new InvoiceItem();
        $quote->addInvoiceItem($invoiceItem);

        //It's A Quote, Assing Current Date, Assing Expiry Date (15 days after today)
        $quote->setQuote(true);
        $quote->setDocumentDate(new \DateTimeImmutable());
        $quote->setExpiryDate($quote->getDocumentDate()->modify("+15 days"));

        //Assign Document Number
        $quotes = $invoiceRepository->findBy(['quote' => true]);
        $documentNumber = 'QOT-' . substr(1000001 + count($quotes),1);
        $quote->setDocumentNumber($documentNumber);

        $form = $this->createForm(QuoteType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Update Quote Amount
            $invoiceItems = $quote->getInvoiceItems();
            $amount = 0;
            foreach ($invoiceItems as $invoiceItem){
                $amount += $invoiceItem->getAmount();
            }
            $quote->setAmount($amount);
            //Persist
            $entityManager->persist($quote);
            $entityManager->flush();

            return $this->redirectToRoute('app_quote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quote/new.html.twig', [
            'quote' => $quote,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_quote_show', methods: ['GET'])]
    public function show(Quote $quote): Response
    {
        return $this->render('quote/show.html.twig', [
            'quote' => $quote,
        ]);
    }

    #[Route('/{uuid}/edit', name: 'app_quote_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quote $quote, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuoteType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quote/edit.html.twig', [
            'quote' => $quote,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_quote_delete', methods: ['POST'])]
    public function delete(Request $request, Quote $quote, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quote->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($quote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quote_index', [], Response::HTTP_SEE_OTHER);
    }
}
