<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserTypeChecklist;
use App\Repository\ChecklistItemRepository;
use App\Repository\ResidenceRepository;
use App\Repository\UserChecklistRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/checklist", name="checklist")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function home(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => '8']);
        $form = $this->createForm(UserTypeChecklist::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }

        return $this->render('checklist.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/manager/collaborators", name="manager_show", methods={"GET"})
     */
    public function showCollaborators(
        UserRepository $userRepository,
        ResidenceRepository $residenceRepository
    ): Response {
        return $this->render('manager/collaborator.html.twig', [
            'residences' => $residenceRepository->findAll(),
            'collaborators' => $userRepository->findBy(
                ['manager' => ['id' => '3']],
                ['lastname' => 'ASC']
            )]);
    }

    /**
     * @Route("/manager/collaborator/{id}", name="collaborator_checklist", methods={"GET"})
     */
    public function showChecklist(
        User $user,
        UserChecklistRepository $userChecklistRepo
    ): Response {
        $checklist = $userChecklistRepo->findBy(['user' => ['id' => $user->getId()]]);

        $items = [];
        foreach ($checklist as $item) {
            $items[] = $item->getChecklistItem()->getName();
        }


        return $this->render('manager/checklist.html.twig', [
            'user' => $user,
            'checklist' => $checklist,
            'items' => $items,
            ]);
    }
}
