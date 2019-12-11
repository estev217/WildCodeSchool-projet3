<?php

namespace App\Controller;

use App\Entity\UserChecklist;
use App\Form\UserChecklistType;
use App\Repository\UserChecklistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/checklist", name="user_checklist")
 */
class UserChecklistController extends AbstractController
{
    /**
     * @Route("/index", name="_index")
     */
    public function index()
    {
        return $this->render('user_checklist/index.html.twig', [
            'controller_name' => 'UserChecklistController',
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @param UserChecklist $userChecklist
     * @return Response
     */
    public function showForManager(UserChecklist $userChecklist): Response
    {
        return $this->render('manager/collaborator.html.twig', [
            'user_checklist' => $userChecklist,
        ]);
    }
}
