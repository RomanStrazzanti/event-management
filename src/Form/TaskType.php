<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Task;
use App\Entity\Vendor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('taskName')
            ->add('deadline', null, [
                'widget' => 'single_text',
            ])
            ->add('status')
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'id',
            ])
            ->add('assignedTo', EntityType::class, [
                'class' => Vendor::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
