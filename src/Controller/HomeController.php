<?php


namespace App\Controller;

use App\Entity\IntegrationStep;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserChecklist;
use App\Form\UserChecklistType;
use App\Form\UserChecklistType2;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
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
        $form = $this->createForm(UserType::class);

        return $this->render('login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home", name="home")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function home(Request $request, EntityManagerInterface $em): Response
    {
        $checklist = new UserChecklist();
        $formTodo = $this->createForm(UserChecklistType::class, $checklist, ['em' => $em]);

        $formDoc = $this->createForm(UserChecklistType2::class, $checklist, ['em' => $em]);

        return $this->render('checklist.html.twig', [
            'formTodo' => $formTodo->createView(),
            'formDoc' => $formDoc->createView(),
        ]);
    }
}
