<?php

namespace App\Controller;

use App\Entity\ChecklistItem;
use App\Entity\IntegrationStep;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserTypeChecklist;
use App\Entity\Role;
use App\Repository\ResidenceRepository;
use App\Repository\UserRepository;
use App\Service\TimelineService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profile/{user}", name="profile")
     * @param User $user
     * @param TimelineService $timelineService
     * @return Response
     */
    public function profile(User $user, TimelineService $timelineService): Response
    {
        //Checklist progress bar
        $totalItems = count($this->getDoctrine()->getRepository(ChecklistItem::class)->findAll());
        $userItems = count($user->getChecklistItems());

        $checklist = ($userItems * 100) / $totalItems;

        //Integration progress bar
        $steps = $this->getDoctrine()->getRepository(IntegrationStep::class)->findAll();
        $totalSteps = count($steps);
        $startDate = $user->getStartDate();
        $statuses = $timelineService->generate($steps, $startDate);
        $userSteps = (array_count_values($statuses)['completed']);

        $integration = ($userSteps * 100) / $totalSteps;

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'checklist' => $checklist,
            'integration' => $integration,
        ]);
    }

    /**
     * @Route("/checklist/{user}", name="checklist")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param User $user
     * @return Response
     */
    public function checklist(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $form = $this->createForm(UserTypeChecklist::class, $user, ['write_right' => true]);
        $form->handleRequest($request);

        $totalItems = count($this->getDoctrine()->getRepository(ChecklistItem::class)->findAll());
        $userItems = count($user->getChecklistItems());

        $percent = ($userItems * 100) / $totalItems;

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'success',
                'Vos changements ont été sauvegardés !'
            );
            $entityManager->flush();
        }
        return $this->render('checklist.html.twig', [
            'form' => $form->createView(),
            'percent' => $percent,
        ]);
    }

    /**
     * @Route("/timeline/{user}", name="timeline")
     * @param User $user
     * @param TimelineService $timelineService
     * @return Response
     */
    public function timeline(User $user, TimelineService $timelineService): Response
    {
        $steps = $this->getDoctrine()->getRepository(IntegrationStep::class)->findAll();
        $startDate = $user->getStartDate();

        $statuses = $timelineService->generate($steps, $startDate);

        return $this->render('timeline/timeline.html.twig', [
            'steps' => $steps,
            'statuses' => $statuses,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{user}/collaborators", name="manager_show", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function showCollaborators(User $user): Response
    {
        return $this->render('manager/collaborator.html.twig', [
            'collaborators' => $user->getCollaborators(),
        ]);
    }

    /**
     * @Route("/manager/collaborator/{user}", name="collaborator_checklist", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function showCollaboratorChecklist(User $user): Response
    {
        $form = $this->createForm(UserTypeChecklist::class, $user);

        $totalItems = count($this->getDoctrine()->getRepository(ChecklistItem::class)->findAll());
        $userItems = count($user->getChecklistItems());

        $percent = ($userItems * 100) / $totalItems;

        return $this->render('checklist.html.twig', [
            'collaborator' => $user,
            'form' => $form->createView(),
            'percent' => $percent,
        ]);
    }


    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
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
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRole()->getName() === 'Collaborateur') {
                $user->setRoles(['ROLE_COLLABORATOR']);
            }
            if ($user->getRole()->getName() === 'Manager') {
                $user->setRoles(['ROLE_MANAGER']);
            }
            if ($user->getRole()->getName() === 'Administrateur') {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $plainPassword = $user->getPassword();
            $encoded = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

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
     * @param User $user
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRole()->getName() === 'Collaborateur') {
                $user->setRoles(['ROLE_COLLABORATOR']);
            }
            if ($user->getRole()->getName() === 'Manager') {
                $user->setRoles(['ROLE_MANAGER']);
            }
            if ($user->getRole()->getName() === 'Administrateur') {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $plainPassword = $user->getPassword();
            $encoded = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

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
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
