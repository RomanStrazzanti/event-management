<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client')]
class ClientController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/new', name: 'client_new')]
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            return $this->redirectToRoute('client_list'); // Assurez-vous que cette route existe
        }

        return $this->render('client/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'client_list')]
    public function index(): Response
    {
        $clients = $this->entityManager->getRepository(Client::class)->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/edit/{id}', name: 'client_edit')]
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('client_list');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'client_delete', methods: ['POST'])]
    public function delete(Client $client): Response
    {
        $this->entityManager->remove($client);
        $this->entityManager->flush();

        return $this->redirectToRoute('client_list');
    }
}
