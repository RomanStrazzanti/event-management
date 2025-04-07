<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventPartner;
use App\Entity\Forfait;
use App\Entity\Partner;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventPartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amountPaid')
            ->add('paymentDate', null, [
                'widget' => 'single_text',
            ])
            ->add('notes')
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'id',
            ])
            ->add('partner', EntityType::class, [
                'class' => Partner::class,
                'choice_label' => 'id',
            ])
            ->add('forfait', EntityType::class, [
                'class' => Forfait::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventPartner::class,
        ]);
    }
}
