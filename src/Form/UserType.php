<?php

namespace App\Form;

use App\Entity\Residence;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('password')
            ->add('picture')
            ->add('mentor')
            ->add('referent')
            ->add('startDate')
            ->add('position')
            ->add('role', EntityType::class, [
                    'class' => Role::class,
                    'choice_label' => 'name',
                ])
            ->add('manager', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
            ])
            ->add('residence', EntityType::class, [
                'class' => Residence::class,
                'choice_label' => 'name',
            ])
            ->add('residencePilot', EntityType::class, [
                'class' => Residence::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}