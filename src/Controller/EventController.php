<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Partner;
use App\Entity\Forfait;
use App\Entity\EventPartner;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/event')]
final class EventController extends AbstractController
{
    #[Route(name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
    
        // Récupère tous les partenaires pour affichage
        $partners = $entityManager->getRepository(Partner::class)->findAll();
    
        // Récupère tous les forfaits pour affichage
        $allForfaits = $entityManager->getRepository(Forfait::class)->findAll();
    
        // Récupère l'ID du partenaire et du forfait associés (si existants)
        $selectedPartnerId = $event->getEventPartners()->first() ? $event->getEventPartners()->first()->getPartner()->getId() : null;
        $selectedForfaitId = $event->getEventPartners()->first() ? $event->getEventPartners()->first()->getForfait()->getId() : null;
        $amountPaid = $event->getEventPartners()->first() ? $event->getEventPartners()->first()->getAmountPaid() : 0;
        $paymentDate = $event->getEventPartners()->first() ? $event->getEventPartners()->first()->getPaymentDate() : null;
        $notes = $event->getEventPartners()->first() ? $event->getEventPartners()->first()->getNotes() : '';
    
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedPartnerId = $request->request->get('partner'); // ID du partenaire sélectionné
            $selectedForfaitId = $request->request->get('forfait'); // ID du forfait sélectionné
            $amountPaid = $request->request->get('amountPaid'); // Montant payé
            $paymentDate = $request->request->get('paymentDate'); // Date de paiement
            $notes = $request->request->get('notes'); // Notes
    
            // Supprimer tous les anciens liens
            foreach ($event->getEventPartners() as $eventPartner) {
                $entityManager->remove($eventPartner);
            }
    
            // Ajouter le nouveau lien sélectionné pour le partenaire et le forfait
            if ($selectedPartnerId && $selectedForfaitId) {
                $partner = $entityManager->getRepository(Partner::class)->find($selectedPartnerId);
                $forfait = $entityManager->getRepository(Forfait::class)->find($selectedForfaitId);
    
                if ($partner && $forfait) {
                    $eventPartner = new EventPartner();
                    $eventPartner->setEvent($event);
                    $eventPartner->setPartner($partner);
                    $eventPartner->setForfait($forfait);
                    $eventPartner->setAmountPaid((float)$amountPaid); // Set le montant payé
                    $eventPartner->setPaymentDate($paymentDate ? new \DateTime($paymentDate) : null); // Set la date de paiement
                    $eventPartner->setNotes($notes); // Set les notes
                    $entityManager->persist($eventPartner);
                    $event->addEventPartner($eventPartner);
                }
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'partners' => $partners,
            'all_forfaits' => $allForfaits,
            'selectedPartnerId' => $selectedPartnerId,
            'selectedForfaitId' => $selectedForfaitId,
            'amountPaid' => $amountPaid,
            'paymentDate' => $paymentDate ? $paymentDate->format('Y-m-d') : null,  // Format correct pour affichage
            'notes' => $notes,
        ]);
    }
    
     

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/event/{eventId}/add-partner/{partnerId}', name: 'app_event_partner_add')]
    public function addPartner(int $eventId, int $partnerId, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($eventId);
        $partner = $entityManager->getRepository(Partner::class)->find($partnerId);

        if ($event && $partner) {
            $eventPartner = new EventPartner();
            $eventPartner->setEvent($event);
            $eventPartner->setPartner($partner);
            $entityManager->persist($eventPartner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_edit', ['id' => $eventId]);
    }

    #[Route('/event/{eventId}/remove-partner/{partnerId}', name: 'app_event_partner_remove')]
    public function removePartner(int $eventId, int $partnerId, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($eventId);
        $partner = $entityManager->getRepository(Partner::class)->find($partnerId);

        if ($event && $partner) {
            $eventPartner = $entityManager->getRepository(EventPartner::class)->findOneBy(['event' => $event, 'partner' => $partner]);
            if ($eventPartner) {
                $entityManager->remove($eventPartner);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_event_edit', ['id' => $eventId]);
    }

    #[Route('/event/{eventId}/add-forfait/{forfaitId}', name: 'app_event_forfait_add')]
    public function addForfait(int $eventId, int $forfaitId, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($eventId);
        $forfait = $entityManager->getRepository(Forfait::class)->find($forfaitId);

        if ($event && $forfait) {
            // Associer le forfait à l'événement ici
            // Vous pouvez aussi créer une entité EventForfait pour gérer cette relation si nécessaire.
        }

        return $this->redirectToRoute('app_event_edit', ['id' => $eventId]);
    }

}
