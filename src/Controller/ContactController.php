<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\ContactAddress;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contact')]
final class ContactController extends AbstractController
{
    #[Route(name: 'app_contact_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $billingAddress = new ContactAddress();
        $billingAddress->setType('Billing');
        $contact->addContactAddress($billingAddress);
        $form = $this->createForm(ContactType::class, $contact, ['allow_extra_fields' => true]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $displayName = $request->request->all()['contact_displayName'];
            $contact->setDisplayName($displayName);
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_contact_show', methods: ['GET'])]
    public function show(Contact $contact, ContactRepository $contactRepository, InvoiceRepository $invoiceRepository): Response
    {
        $pendingInvoices = $invoiceRepository->findUnpaidClientInvoices($contact->getId());

        return $this->render('contact/show.html.twig', [
            'contacts' => $contactRepository->findAll(),
            'contact' => $contact,
            'pendingInvoices' => $pendingInvoices
        ]);
    }

    #[Route('/{uuid}/edit', name: 'app_contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if(count($contact->getContactAddresses()) === 0){
            $billingAddress = new ContactAddress();
            $billingAddress->setType('Billing');
            $contact->addContactAddress($billingAddress);
        }
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $displayName = $request->request->all()['contact_displayName'];
            $contact->setDisplayName($displayName);

            $entityManager->flush();

            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_contact_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
