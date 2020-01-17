<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Form\VerifyPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{

    /**
     * @Route("/user/reset/{user}", name="password_reset", methods={"GET","POST"})
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function reset(User $user, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $user->getPassword();
            $encoded = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($encoded);

            $manager = $this->getDoctrine()->getManager();

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Nouveau mot de passe enregistré !');

            return new RedirectResponse($this->generateUrl('profile', [
                'user' => $user->getId(),
            ]));
        }
        return $this->render('security/reset.html.twig', [
            'form' => $form->createView(),
            'User' => $user,
        ]);
    }

    /**
     * @Route("/user/verify/{user}", name="password_verify", methods={"GET","POST"})
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function verify(User $user, Request $request): Response
    {
        $hash = $user->getPassword();
        $form = $this->createForm(VerifyPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (password_verify($form['password']->getData(), $hash)) {
                return new RedirectResponse($this->generateUrl('password_reset', [
                    'user' => $user->getId(),
                ]));
            } else {
                $this->addFlash('danger', "Le mot de passe n'est pas bon !");
            }
        }

        return $this->render('security/verify.html.twig', [
            'form' => $form->createView(),
            'User' => $user,
        ]);
    }
}
