<?php

namespace App\Controller;

use App\Entity\CategotyEvent;
use App\Form\CategotyEventType;
use App\Repository\CategotyEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categoty/event')]
class CategotyEventController extends AbstractController
{
    #[Route('/', name: 'app_categoty_event_index', methods: ['GET'])]
    public function index(CategotyEventRepository $categotyEventRepository): Response
    {
        return $this->render('categoty_event/index.html.twig', [
            'categoty_events' => $categotyEventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categoty_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategotyEventRepository $categotyEventRepository): Response
    {
        $categotyEvent = new CategotyEvent();
        $form = $this->createForm(CategotyEventType::class, $categotyEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categotyEventRepository->add($categotyEvent, true);

            return $this->redirectToRoute('app_categoty_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categoty_event/new.html.twig', [
            'categoty_event' => $categotyEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categoty_event_show', methods: ['GET'])]
    public function show(CategotyEvent $categotyEvent): Response
    {
        return $this->render('categoty_event/show.html.twig', [
            'categoty_event' => $categotyEvent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categoty_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategotyEvent $categotyEvent, CategotyEventRepository $categotyEventRepository): Response
    {
        $form = $this->createForm(CategotyEventType::class, $categotyEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categotyEventRepository->add($categotyEvent, true);

            return $this->redirectToRoute('app_categoty_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categoty_event/edit.html.twig', [
            'categoty_event' => $categotyEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categoty_event_delete', methods: ['POST'])]
    public function delete(Request $request, CategotyEvent $categotyEvent, CategotyEventRepository $categotyEventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categotyEvent->getId(), $request->request->get('_token'))) {
            $categotyEventRepository->remove($categotyEvent, true);
        }

        return $this->redirectToRoute('app_categoty_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
