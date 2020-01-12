<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\User;
use Nette\Neon\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, [
                'label' => ' ',
                'date_widget' => 'choice',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heure', 'minute' => 'Minute',
                ],
                'minutes' => [
                    00, 05, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55
                ],
                'years' => [
                    2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035,
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Collaborateur',
                'choice_label' => function (user $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                }])
            ->add('partner', EntityType::class, [
                'class' => User::class,
                'label' => 'Manager',
                'choice_label' => function (user $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                }])
            ->add('subject', TextType::class, [
                'mapped' => false,
                'label' => 'Objet du message'
            ])
            ->add('message', TextareaType::class, [
                'mapped' => false,
                'label' => 'Votre message',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
