<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'invoice_list', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $invoices = $entityManager->getRepository(Invoice::class)->findAll();
        return $this->render('invoice/index.html.twig', ['invoices' => $invoices]);
    }

    #[Route('/new', name: 'invoice_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invoice);
            $entityManager->flush();
            return $this->redirectToRoute('invoice_list');
        }

        return $this->render('invoice/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/edit/{id}', name: 'invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('invoice_list');
        }

        return $this->render('invoice/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/delete/{id}', name: 'invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('invoice_list');
    }
}
