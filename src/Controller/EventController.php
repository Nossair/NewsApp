<?php

namespace App\Controller;

use App\Entity\CategotyEvent;
use App\Entity\Event;
use App\Entity\OptionDateEvent;
use App\Form\CategotyEventType;
use App\Form\EventType;
use App\Form\OptionDateEventType;
use App\Repository\CategotyEventRepository;
use App\Repository\EventRepository;
use App\Repository\GroupMailRepository;
use App\Repository\OptionDateEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/event')]
class EventController extends AbstractController
{
    private $security;
    private $categotyEventRepository;
    private $groupMailRepository;

    public function __construct(Security $security, CategotyEventRepository $categotyEventRepository, GroupMailRepository $groupMailRepository)
    {
        $this->security = $security;
        $this->categotyEventRepository =$categotyEventRepository;
        $this->groupMailRepository = $groupMailRepository;
    }
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository,OptionDateEventRepository $optionDateEventRepository, GroupMailRepository $groupMailRepository, CategotyEventRepository $categotyEventRepository): Response
    {






        if ($request->isXmlHttpRequest()) {
            $optionsDateEvent =json_decode($request->request->get("optionDateEvent"), true) ;
            $groupMails = json_decode($request->request->get("groupMails"), true) ;
            $event = new Event();
            $event->setUser($this->security->getUser());
            $event->setIsArchived(false);
            $event->setNbrVote(0);
            $event->setCategoryEvent($categotyEventRepository->find($request->request->get("categoryEvent")));
            $event->setName($request->request->get("name"));
            $event->setDescription($request->request->get("description"));
            $event->setDateEndVote(new \DateTime($request->request->get("dateEndVote")));


            foreach ($groupMails as $item){
                $event->addGroupMail( $groupMailRepository->find($item));
            }
            $eventRepository->add($event, true);
            foreach ($optionsDateEvent as $item){
                $optionDateEvent = new OptionDateEvent();
                $optionDateEvent->setNbrVote(0);
                $optionDateEvent->setOptionDate(new \DateTime($item));
                $optionDateEvent->setEvent($eventRepository->find($event));
                $event->addOptionDateEvent($optionDateEvent);
                $optionDateEventRepository->add($optionDateEvent,true);
            }

            return $this->json(['event' => $eventRepository->find($event)->getId()]);
        }
        return $this->renderForm('event/new.html.twig', [
            'groupmails' => $this->groupMailRepository->findAll(),
            'categoryEvents' => $this->categotyEventRepository->findAll(),


        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show( $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->add($event, true);

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    private function getEventCategorieOptions(){
        $categotyEvent = $this->categotyEventRepository->findAll();
        $categotyEventOptions = [];
        foreach ($categotyEvent as $index){
            $categotyEventOptions[] = [$index->getNom() => $index];
        }
        return $categotyEventOptions;
    }
    private function getGroupMailOptions(){
        $groupMail = $this->groupMailRepository->findAll();
        $groupMailOptions = [];
        foreach ($groupMail as $index){
            $groupMailOptions[] = [$index->getName() => $index];
        }
        return $groupMailOptions;
    }


}
