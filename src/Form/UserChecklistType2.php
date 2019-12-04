<?php

namespace App\Form;

use App\Entity\ChecklistItem;
use App\Entity\User;
use App\Entity\UserChecklist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserChecklistType2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('checklistItem', EntityType::class, [
                'class' => ChecklistItem::class,
                'choices' => $options['em']->getRepository(ChecklistItem::class)
                    ->findByCategory(UserChecklist::CATEGORIES[1]),
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'group_by' => 'category',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserChecklist::class,
            'em' => null,
        ]);
    }
}
