<?php

namespace App\Form;

use App\Entity\ChecklistItem;
use App\Entity\Position;
use App\Entity\Residence;
use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('email')
            ->add('password', TextType::class, [
                'label' => 'Mot de passe'
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image',
                'required' => false,
            ])
            ->add('mentor')
            ->add('referent', TextType::class, [
                'label' => 'Référent'
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Date d\'entrée',
                'format' => 'dd-MM-yyyy',
            ])

            ->add('position', EntityType::class, [
                'class' => Position::class,
                'choice_label' => 'name',
                'label' => 'Métier',
            ])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'name',
                'label' => 'Rôle',
            ])
            ->add('manager', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'label' => 'Manager',
            ])
            ->add('residence', EntityType::class, [
                'class' => Residence::class,
                'choice_label' => 'name',
                'label' => 'Résidence',
            ])
            ->add('residencePilote', EntityType::class, [
                'class' => Residence::class,
                'choice_label' => 'name',
                'label' => 'Résidence pilote',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
