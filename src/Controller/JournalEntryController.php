<?php

namespace App\Controller;

use App\Entity\JournalEntry;
use App\Form\JournalEntryType;
use App\Repository\JournalEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/journal_entry')]
final class JournalEntryController extends AbstractController
{
    #[Route(name: 'app_journal_entry_index', methods: ['GET'])]
    public function index(JournalEntryRepository $journalEntryRepository): Response
    {
        return $this->render('journal_entry/index.html.twig', [
            'journal_entries' => $journalEntryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_journal_entry_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $journalEntry = new JournalEntry();
        $form = $this->createForm(JournalEntryType::class, $journalEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($journalEntry);
            $entityManager->flush();

            return $this->redirectToRoute('app_journal_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journal_entry/new.html.twig', [
            'journal_entry' => $journalEntry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_journal_entry_show', methods: ['GET'])]
    public function show(JournalEntry $journalEntry): Response
    {
        return $this->render('journal_entry/show.html.twig', [
            'journal_entry' => $journalEntry,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_journal_entry_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JournalEntry $journalEntry, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JournalEntryType::class, $journalEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_journal_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('journal_entry/edit.html.twig', [
            'journal_entry' => $journalEntry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_journal_entry_delete', methods: ['POST'])]
    public function delete(Request $request, JournalEntry $journalEntry, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$journalEntry->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($journalEntry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_journal_entry_index', [], Response::HTTP_SEE_OTHER);
    }
}
