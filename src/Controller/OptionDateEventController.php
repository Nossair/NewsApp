<?php

namespace App\Controller;

use App\Entity\OptionDateEvent;
use App\Form\OptionDateEventType;
use App\Repository\OptionDateEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/option/date/event')]
class OptionDateEventController extends AbstractController
{
    #[Route('/', name: 'app_option_date_event_index', methods: ['GET'])]
    public function index(OptionDateEventRepository $optionDateEventRepository): Response
    {
        return $this->render('option_date_event/index.html.twig', [
            'option_date_events' => $optionDateEventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_option_date_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OptionDateEventRepository $optionDateEventRepository): Response
    {
        $optionDateEvent = new OptionDateEvent();
        $form = $this->createForm(OptionDateEventType::class, $optionDateEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionDateEventRepository->add($optionDateEvent, true);

            return $this->redirectToRoute('app_option_date_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('option_date_event/new.html.twig', [
            'option_date_event' => $optionDateEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_option_date_event_show', methods: ['GET'])]
    public function show(OptionDateEvent $optionDateEvent): Response
    {
        return $this->render('option_date_event/show.html.twig', [
            'option_date_event' => $optionDateEvent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_option_date_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OptionDateEvent $optionDateEvent, OptionDateEventRepository $optionDateEventRepository): Response
    {
        $form = $this->createForm(OptionDateEventType::class, $optionDateEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionDateEventRepository->add($optionDateEvent, true);

            return $this->redirectToRoute('app_option_date_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('option_date_event/edit.html.twig', [
            'option_date_event' => $optionDateEvent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_option_date_event_delete', methods: ['POST'])]
    public function delete(Request $request, OptionDateEvent $optionDateEvent, OptionDateEventRepository $optionDateEventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$optionDateEvent->getId(), $request->request->get('_token'))) {
            $optionDateEventRepository->remove($optionDateEvent, true);
        }

        return $this->redirectToRoute('app_option_date_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
