<?php

namespace App\Controller;

use App\Entity\TransactionCategory;
use App\Form\TransactionCategoryType;
use App\Repository\TransactionCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transaction/category')]
final class TransactionCategoryController extends AbstractController
{
    #[Route(name: 'app_transaction_category_index', methods: ['GET'])]
    public function index(TransactionCategoryRepository $transactionCategoryRepository): Response
    {
        return $this->render('transaction_category/index.html.twig', [
            'transaction_categories' => $transactionCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_transaction_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $transactionCategory = new TransactionCategory();
        $form = $this->createForm(TransactionCategoryType::class, $transactionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transactionCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction_category/new.html.twig', [
            'transaction_category' => $transactionCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_category_show', methods: ['GET'])]
    public function show(TransactionCategory $transactionCategory): Response
    {
        return $this->render('transaction_category/show.html.twig', [
            'transaction_category' => $transactionCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TransactionCategory $transactionCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionCategoryType::class, $transactionCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction_category/edit.html.twig', [
            'transaction_category' => $transactionCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_category_delete', methods: ['POST'])]
    public function delete(Request $request, TransactionCategory $transactionCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transactionCategory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($transactionCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transaction_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
