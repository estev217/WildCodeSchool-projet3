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
        return $this->redirectToRoute('app_login');
    }
}
