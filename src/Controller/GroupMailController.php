<?php

namespace App\Controller;

use App\Entity\GroupMail;
use App\Form\GroupMailType;
use App\Repository\GroupMailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/group/mail')]
class GroupMailController extends AbstractController
{
    #[Route('/', name: 'app_group_mail_index', methods: ['GET'])]
    public function index(GroupMailRepository $groupMailRepository): Response
    {
        return $this->render('group_mail/index.html.twig', [
            'group_mails' => $groupMailRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_group_mail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GroupMailRepository $groupMailRepository): Response
    {
        $groupMail = new GroupMail();
        $form = $this->createForm(GroupMailType::class, $groupMail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupMailRepository->add($groupMail, true);

            return $this->redirectToRoute('app_group_mail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('group_mail/new.html.twig', [
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
}
