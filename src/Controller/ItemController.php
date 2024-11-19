<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/item')]
final class ItemController extends AbstractController
{
    #[Route(name: 'app_item_index', methods: ['GET'])]
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_item_show', methods: ['GET'])]
    public function show(Item $item, ItemRepository $itemRepository): Response
    {
        $itemInvoices = $item->getInvoiceItems();

        $itemAmount = [
            'paid' => 0,
            'pending' => 0,
        ];

        foreach ($itemInvoices as $invoiceItem){
            $invoice = $invoiceItem->getInvoice();
            if($invoice->getInvoiceStatus() === 'Paid'){
                $itemAmount['paid'] += $item->getRegularPrice();
            } else {
                $itemAmount['pending'] += $item->getRegularPrice();
            }
        }

        return $this->render('item/show.html.twig', [
            'item' => $item,
            'items' => $itemRepository->findAll(),
            'item_amount' => $itemAmount
        ]);
    }

    #[Route('/{uuid}/price', name: 'app_item_price', methods: ['GET'])]
    public function getPrice(Item $item, ItemRepository $itemRepository): Response
    {
        $data = ['price' => $item->getRegularPrice()];
        return new JsonResponse($data);
    }

    #[Route('/{uuid}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/{uuid}', name: 'app_item_delete', methods: ['POST'])]
    public function delete(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        $confirmDelete = $request->request->get('confirm_delete');
        if ($confirmDelete === 'delete' && $this->isCsrfTokenValid('delete'.$item->getUuid(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($item);
            $entityManager->flush();
        }

        $referer = $request->headers->get('referer');
        if ($referer) {
            return new RedirectResponse($referer);
        }

        return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
    }
}
