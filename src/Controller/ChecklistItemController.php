<?php

namespace App\Controller;

use App\Entity\ChecklistItem;
use App\Form\ChecklistItemType;
use App\Repository\ChecklistItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checklistitem", name="checklist_item_")
 */
class ChecklistItemController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     * @param ChecklistItemRepository $checklistItemRepository
     * @return Response
     */
    public function index(ChecklistItemRepository $checklistItemRepository): Response
    {
        return $this->render('checklist_item/index.html.twig', [
            'checklist_items' => $checklistItemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $checklistItem = new ChecklistItem();
        $form = $this->createForm(ChecklistItemType::class, $checklistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($checklistItem);
            $entityManager->flush();

            return $this->redirectToRoute('checklist_item_index');
        }

        return $this->render('checklist_item/new.html.twig', [
            'checklist_item' => $checklistItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param ChecklistItem $checklistItem
     * @return Response
     */
    public function show(ChecklistItem $checklistItem): Response
    {
        return $this->render('checklist_item/show.html.twig', [
            'checklist_item' => $checklistItem,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param ChecklistItem $checklistItem
     * @return Response
     */
    public function edit(Request $request, ChecklistItem $checklistItem): Response
    {
        $form = $this->createForm(ChecklistItemType::class, $checklistItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('checklist_item_index');
        }

        return $this->render('checklist_item/edit.html.twig', [
            'checklist_item' => $checklistItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param ChecklistItem $checklistItem
     * @return Response
     */
    public function delete(Request $request, ChecklistItem $checklistItem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$checklistItem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($checklistItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('checklist_item_index');
    }
}
