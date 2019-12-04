<?php


namespace App\Controller;

use App\Form\UserChecklistType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/home", name="home")
     * @param Request $request
     * @return Response
     */
    public function home(Request $request): Response
    {
        $form = $this->createForm(UserChecklistType::class);
        $form->handleRequest($request);

        return $this->render('checklist.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
