<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SummaryAppointmentType;
use App\Repository\UserRepository;
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/appointment")
 */
class AppointmentController extends AbstractController
{

    public function nextAppointments(AppointmentRepository $appointmentRepository, User $user): Response
    {
        $allAppointments = '';
        if ($this->isGranted('ROLE_MANAGER')) {
            $allAppointments = $appointmentRepository->findBy(['partner' => $user->getId()]);
        } elseif ($this->isGranted('ROLE_COLLABORATOR')) {
            $allAppointments = $appointmentRepository->findBy(['user' => $user->getId()]);
        }

        $today = new DateTime();

        $nextAppointments = [];

        foreach ($allAppointments as $appointment) {
            if ($appointment->getDate() > $today) {
                $nextAppointments[] = $appointment;
            }
        }

        usort($nextAppointments, function ($a, $b) {
            return ($a->getDate()) <=> ($b->getDate());
        });

        return $this->render('appointment/_next.html.twig', [
            'nextAppointments' => array_slice($nextAppointments, 0, 2),
        ]);
    }

    /**
     * @Route("/admin/index", name="appointment_index", methods={"GET"})
     */
    public function index(AppointmentRepository $appointmentRepository): Response
    {
        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="appointment_new", methods={"GET","POST"})
     */
    public function new(Request $request, ParameterBagInterface $parameterBag, User $collaborator): Response
    {
        $manager = $this->getUser();
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $appointment->setPartner($manager);
            $appointment->setUser($collaborator);
            $entityManager->persist($appointment);
            $entityManager->flush();

            $date = date_format(($form['date']->getData()), 'd-m-Y H:i');

            $mail = new PHPMailer(true);

                /*Enable verbose debug output*/
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                /* Tells PHPMailer to use SMTP. */
                $mail->isSMTP();
                /* SMTP server address. */
                $mail->Host = $this->getParameter('mail_server');
                /* Use SMTP authentication. */
                $mail->SMTPAuth = true;
                /* SMTP authentication username. */
                $mail->Username = $this->getParameter('mail_from');
                /* SMTP authentication password. */
                $mail->Password = $this->getParameter('mail_password');
                /* Set the encryption system. */
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                /* Set the SMTP port. */
                $mail->Port = 587;
                $mail->setFrom($this->getParameter('mail_from'));
                $mail->addAddress($this->getParameter('mail_from'));
                //$mail->addAddress($form['user']->getData()->getEmail());
                /*$mail->addCC($manager->getEmail());
                $mail->addReplyTo($manager->getEmail());*/
                $mail->isHTML(true);
                $mail->Subject = 'Votre rendez-vous du '. $date . ' ' . utf8_decode($form['subject']->getData());
                $mail->Body = utf8_decode($form['message']->getData());

                /* Disable some SSL checks. */
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->send();

                $this->addFlash('success', 'Rendez-vous et e-mail envoyés !');

                return new RedirectResponse($this->generateUrl('manager_show', [
                'user' => $manager->getId(),
                ]));
        }

        return $this->render('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
            'collaborator' => $collaborator,
        ]);
    }

    /**
     * @Route("/admin/{id}", name="appointment_show", methods={"GET"})
     */
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="appointment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Appointment $appointment): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('appointment_index');
        }

        return $this->render('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/summary/edit/{id}", name="edit_summary", methods={"GET","POST"})
     */
    public function editSummary(Request $request, Appointment $appointment): Response
    {
        $form = $this->createForm(SummaryAppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new RedirectResponse($this->generateUrl('profile', [
                'user' =>$this->getUser()->getId(),
            ]));
        }

        return $this->render('appointment/summary_appointment.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/summary/{id}", name="show_summary", methods={"GET","POST"})
     */
    public function showSummary(Appointment $appointment)
    {
        return $this->render('appointment/show_summary.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * @Route("/admin/{id}", name="appointment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Appointment $appointment): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appointment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appointment_index');
    }
}
