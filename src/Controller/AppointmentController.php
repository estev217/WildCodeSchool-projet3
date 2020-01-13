<?php

namespace App\Controller;

use App\Entity\User;
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
            $allAppointments = $user->getAppointments();
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
            'nextAppointments' => $nextAppointments,
            'ok' => 'ok',
        ]);
    }

    /**
     * @Route("/", name="appointment_index", methods={"GET"})
     */
    public function index(AppointmentRepository $appointmentRepository): Response
    {
        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="appointment_new", methods={"GET","POST"})
     */
    public function new(Request $request, ParameterBagInterface $parameterBag): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $entityManager->flush();

            $mail = new PHPMailer(true);
            /*Uncomment next line to see SMTP debug after process*/
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            /* Tells PHPMailer to use SMTP. */
            $mail->isSMTP();
            /* SMTP server address. */
            $mail->Host = 'smtp-mail.outlook.com';
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
            //$mail->addAddress($form['user']->getData()->getEmail());
            $mail->addAddress($this->getParameter('mail_from'));
            $mail->isHTML(true);
            $mail->Subject = 'Rendez-vous du';
            $mail->Body = $form['message']->getData();
            /*$mail->AltBody = '';*/

            /* Disable some SSL checks. */
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->send();

            return $this->redirectToRoute('appointment_index');
        }

        return $this->render('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="appointment_show", methods={"GET"})
     */
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="appointment_edit", methods={"GET","POST"})
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
     * @Route("/{id}", name="appointment_delete", methods={"DELETE"})
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
