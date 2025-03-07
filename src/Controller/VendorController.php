<?php

namespace App\Controller;

use App\Entity\Vendor;
use App\Form\VendorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vendor')]
class VendorController extends AbstractController
{
    #[Route('/', name: 'vendor_list', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $vendors = $entityManager->getRepository(Vendor::class)->findAll();
        return $this->render('vendor/index.html.twig', ['vendors' => $vendors]);
    }

    #[Route('/create', name: 'vendor_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vendor = new Vendor();
        $form = $this->createForm(VendorType::class, $vendor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vendor);
            $entityManager->flush();
            return $this->redirectToRoute('vendor_list');
        }

        return $this->render('vendor/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/edit/{id}', name: 'vendor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vendor $vendor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VendorType::class, $vendor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('vendor_list');
        }

        return $this->render('vendor/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/delete/{id}', name: 'vendor_delete', methods: ['POST'])]
    public function delete(Request $request, Vendor $vendor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vendor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vendor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vendor_list');
    }
}
