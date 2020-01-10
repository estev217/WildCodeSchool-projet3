<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(user $user) {
                return $user->getFirstname() . ' ' . $user->getLastname();
                }])
            ->add('partner', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(user $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                }])
            ->add( 'message', null, ['mapped' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
