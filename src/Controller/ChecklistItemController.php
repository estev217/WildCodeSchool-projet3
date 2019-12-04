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
 * @Route("/checklist/item")
 */
class ChecklistItemController extends AbstractController
{
    /**
     * @Route("/", name="checklist_item_index", methods={"GET"})
     */
    public function index(ChecklistItemRepository $checklistItemRepository): Response
    {
        return $this->render('checklist_item/index.html.twig', [
            'checklist_items' => $checklistItemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="checklist_item_new", methods={"GET","POST"})
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
     * @Route("/{id}", name="checklist_item_show", methods={"GET"})
     */
    public function show(ChecklistItem $checklistItem): Response
    {
        return $this->render('checklist_item/show.html.twig', [
            'checklist_item' => $checklistItem,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="checklist_item_edit", methods={"GET","POST"})
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
     * @Route("/{id}", name="checklist_item_delete", methods={"DELETE"})
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
