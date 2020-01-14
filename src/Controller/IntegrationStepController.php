<?php

namespace App\Controller;

use App\Entity\IntegrationStep;
use App\Entity\User;
use App\Form\IntegrationStepType;
use App\Repository\IntegrationStepRepository;
use App\Service\TimelineService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/integration")
 */
class IntegrationStepController extends AbstractController
{
    /**
     * @Route("/timeline/{user}", name="timeline")
     * @param User $user
     * @param TimelineService $timelineService
     * @return Response
     */
    public function timeline(
        User $user,
        TimelineService $timelineService,
        IntegrationStepRepository $integrationStepRepository
    ): Response {
        $steps = $integrationStepRepository->findBy([], ['number' => 'ASC']);
        $startDate = $user->getStartDate();

        $statuses = $timelineService->generate($steps, $startDate);

        return $this->render('timeline/timeline.html.twig', [
            'steps' => $steps,
            'statuses' => $statuses,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/", name="integration_step_index", methods={"GET"})
     */
    public function index(IntegrationStepRepository $integrationStepRepository): Response
    {
        $steps = $integrationStepRepository->findBy([], ['number' => 'ASC']);
        return $this->render('integration_step/index.html.twig', [
            'integration_steps' => $steps,
        ]);
    }

    /**
     * @Route("/new", name="integration_step_new", methods={"GET","POST"})
     * @param Request $request
     * @param TimelineService $timelineService
     * @param IntegrationStepRepository $integrationStepRepository
     * @return Response
     */
    public function new(
        Request $request,
        TimelineService $timelineService,
        IntegrationStepRepository $integrationStepRepository
    ): Response {
        $integrationStep = new IntegrationStep();
        $form = $this->createForm(IntegrationStepType::class, $integrationStep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $steps = $integrationStepRepository->findBy([], ['number' => 'ASC']);
            $timelineService->rearrange($steps, $integrationStep);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($integrationStep);
            $entityManager->flush();

            return $this->redirectToRoute('integration_step_index');
        }

        return $this->render('integration_step/new.html.twig', [
            'integration_step' => $integrationStep,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="integration_step_show", methods={"GET"})
     */
    public function show(IntegrationStep $integrationStep): Response
    {
        return $this->render('integration_step/show.html.twig', [
            'integration_step' => $integrationStep,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="integration_step_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, IntegrationStep $integrationStep): Response
    {
        $form = $this->createForm(IntegrationStepType::class, $integrationStep);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('integration_step_index');
        }

        return $this->render('integration_step/edit.html.twig', [
            'integration_step' => $integrationStep,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="integration_step_delete", methods={"DELETE"})
     */
    public function delete(Request $request, IntegrationStep $integrationStep): Response
    {
        if ($this->isCsrfTokenValid('delete'.$integrationStep->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($integrationStep);
            $entityManager->flush();
        }

        return $this->redirectToRoute('integration_step_index');
    }
}
