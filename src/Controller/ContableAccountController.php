<?php

namespace App\Controller;

use App\Entity\ContableAccount;
use App\Form\ContableAccountType;
use App\Repository\ContableAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contable_account')]
final class ContableAccountController extends AbstractController
{
    #[Route(name: 'app_contable_account_index', methods: ['GET'])]
    public function index(ContableAccountRepository $contableAccountRepository): Response
    {
        return $this->render('contable_account/index.html.twig', [
            'contable_accounts' => $contableAccountRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_contable_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contableAccount = new ContableAccount();
        $form = $this->createForm(ContableAccountType::class, $contableAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contableAccount);
            $entityManager->flush();

            return $this->redirectToRoute('app_contable_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contable_account/new.html.twig', [
            'contable_account' => $contableAccount,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_contable_account_show', methods: ['GET'])]
    public function show(ContableAccount $contableAccount): Response
    {
        return $this->render('contable_account/show.html.twig', [
            'contable_account' => $contableAccount,
        ]);
    }

    #[Route('/{uuid}/edit', name: 'app_contable_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ContableAccount $contableAccount, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContableAccountType::class, $contableAccount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contable_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contable_account/edit.html.twig', [
            'contable_account' => $contableAccount,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_contable_account_delete', methods: ['POST'])]
    public function delete(Request $request, ContableAccount $contableAccount, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contableAccount->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contableAccount);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contable_account_index', [], Response::HTTP_SEE_OTHER);
    }
}
