<?php

namespace App\Controller;

use App\Entity\Lists;
use App\Entity\Tasks;
use App\Form\ListsType;
use App\Form\TasksType;
use App\Repository\ListsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]

#[Route('/lists')]
class ListsController extends AbstractController
{
    #[Route('/', name: 'app_lists_index', methods: ['GET'])]
    public function index(ListsRepository $listsRepository): Response
    {
        $user = $this->getUser();
        if ($user) {
            $lists = $listsRepository->findBy(['user' => $user]);
            return $this->render('lists/index.html.twig', [
                'lists' => $lists
            ]);
        }


    }

    #[Route('/new', name: 'app_lists_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $list = new Lists();
        $user = $this->getUser();
        $list->setUser($user);
        $form = $this->createForm(ListsType::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($list);
            $entityManager->flush();

            return $this->redirectToRoute('app_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lists/new.html.twig', [
            'list' => $list,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lists_show', methods: ['GET', 'POST'])]

    public function show($id, Lists $list, Request $request, EntityManagerInterface $entityManager, ListsRepository $listsRepository): Response
    {
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);

        $form->handleRequest($request);
        $user = $this->getUser();

        if ($user && $form->isSubmitted() && $form->isValid()) {

            $task->setLists($listsRepository->find($id));

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_lists_show', ['id' => $list->getId()]);
        }

        return $this->render('lists/show.html.twig', [
            'list' => $list,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lists_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lists $list, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ListsType::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_lists_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lists/edit.html.twig', [
            'list' => $list,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_lists_delete', methods: ['POST'])]
    public function delete(Request $request, Lists $list, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $list->getId(), $request->request->get('_token'))) {
            $entityManager->remove($list);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_lists_index', [], Response::HTTP_SEE_OTHER);
    }
}
