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
use Doctrine\Persistence\ManagerRegistry;
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

    #[Route('/eventTo', name: 'app_event_to', methods: ['GET'])]
    public function eventTo(Request $request, EventRepository $eventRepository,OptionDateEventRepository $optionDateEventRepository, GroupMailRepository $groupMailRepository, CategotyEventRepository $categotyEventRepository): Response
    {
        $IdsGroupmail = '(';
         $groupMails=$groupMailRepository->findByUser($this->security->getUser());
         foreach ($groupMails as $item){
             $IdsGroupmail  .= $item->getId().',';
         }
        $IdsGroupmail = trim($IdsGroupmail,',');
        $IdsGroupmail .=')';
        $eventInvited = $eventRepository->findByGroupMail($IdsGroupmail);
        return $this->render('event/event_to.html.twig', [
            'events' => $eventInvited,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(EventRepository $eventRepository,Request $request ): Response
    {
        $event = $eventRepository->find($request->get('id'));
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(ManagerRegistry $doctrine,Request $request, EventRepository $eventRepository,OptionDateEventRepository $optionDateEventRepository, GroupMailRepository $groupMailRepository, CategotyEventRepository $categotyEventRepository): Response
    {
        $entityManager = $doctrine->getManager();
        $event = $entityManager->getRepository(Event::class)->find($request->get('id'));
        if ($request->isXmlHttpRequest()) {
            $entityManager = $doctrine->getManager();
            $optionsDateEvent = json_decode($request->request->get("optionDateEvent"), true);
            $groupMails = json_decode($request->request->get("groupMails"), true);
            $event->setCategoryEvent($categotyEventRepository->find($request->request->get("categoryEvent")));
            $event->setName($request->request->get("name"));
            $event->setDescription($request->request->get("description"));
            $event->setDateEndVote(new \DateTime($request->request->get("dateEndVote")));


            foreach ($groupMails as $item) {
                if( !in_array($groupMailRepository->find($item),$event->getGroupMails()->getValues()))
                $event->addGroupMail($groupMailRepository->find($item));
            }

            foreach ($event->getGroupMails()->getValues()as $item) {
                if( !in_array($item->getId(),$groupMails ))
                    $event->removeGroupMail($item);
            }
            $entityManager->flush();
            foreach ($optionsDateEvent as $item) {
                if(!in_array($optionDateEventRepository->findOneBy(['optionDate'=>new \DateTime($item)]),$event->getOptionDateEvent()->getValues())){
                    $optionDateEvent = new OptionDateEvent();
                    $optionDateEvent->setNbrVote(0);
                    $optionDateEvent->setOptionDate(new \DateTime($item));
                    $optionDateEvent->setEvent($eventRepository->find($event));
                    $event->addOptionDateEvent($optionDateEvent);
                    $optionDateEventRepository->add($optionDateEvent, true);
                }

            }
            foreach ($event->getOptionDateEvent()->getValues() as $item) {
                if(!in_array(date_format($item->getOptionDate(),"m/d/Y"),$optionsDateEvent)){
                    $event->removeOptionDateEvent($item);
                    $optionDateEventRepository->remove($item, true);
                }


            }
            return $this->json(['event' => $eventRepository->find($event)->getId()]);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' =>$event,

            'groupmails' => $this->groupMailRepository->findAll(),
            'categoryEvents' => $this->categotyEventRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, EventRepository $eventRepository, OptionDateEventRepository $optionDateEventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$request->get('id'), $request->request->get('_token'))) {
            $optionDateEventRepository->removeByEvent($request->get('id'));
            $eventRepository->remove($eventRepository->find($request->get('id')), true);
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
