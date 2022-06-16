<?php

namespace App\Controller;

use App\Entity\GroupMail;
use App\Form\GroupMailType;
use App\Repository\CategotyEventRepository;
use App\Repository\GroupMailRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/group/mail')]
class GroupMailController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    #[Route('/', name: 'app_group_mail_index', methods: ['GET'])]
    public function index(GroupMailRepository $groupMailRepository): Response
    {
        return $this->render('group_mail/index.html.twig', [
            'group_mails' => $groupMailRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_group_mail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GroupMailRepository $groupMailRepository, UserRepository $userRepository): Response
    {
        $groupMail = new GroupMail();
        $form = $this->createForm(GroupMailType::class, $groupMail)
        ->add('users',ChoiceType::class,[
            'choices' => $this->getUsersOption()
        ]);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest())  {
            $users =json_decode($request->request->get("users"), true) ;
            $groupMail->setName($request->request->get("name"));
            foreach ($users as $item){
                $groupMail->addUser($userRepository->find($item));
            }
            $groupMailRepository->add($groupMail, true);

            return $this->json(['groupMail' => $groupMailRepository->find($groupMail)->getId()]);
        }

        return $this->renderForm('group_mail/new.html.twig', [
            'user_option' => $this->getUsersOption(),
            'group_mail' => $groupMail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_group_mail_show', methods: ['GET'])]
    public function show(GroupMail $groupMail): Response
    {
        return $this->render('group_mail/show.html.twig', [
            'group_mail' => $groupMail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_group_mail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GroupMail $groupMail, GroupMailRepository $groupMailRepository): Response
    {
        $form = $this->createForm(GroupMailType::class, $groupMail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupMailRepository->add($groupMail, true);

            return $this->redirectToRoute('app_group_mail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('group_mail/edit.html.twig', [
            'group_mail' => $groupMail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_group_mail_delete', methods: ['POST'])]
    public function delete(Request $request, GroupMail $groupMail, GroupMailRepository $groupMailRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$groupMail->getId(), $request->request->get('_token'))) {
            $groupMailRepository->remove($groupMail, true);
        }

        return $this->redirectToRoute('app_group_mail_index', [], Response::HTTP_SEE_OTHER);
    }
    public function getUsersOption(){
        $users = $this->userRepository->findAll();
        $userOptions = [];
        foreach ($users as $index){
            $userOptions[$index->getEmail()] = $index->getId();
        }
        return $userOptions;
    }
}
