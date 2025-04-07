<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use App\Entity\Tarif;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserTarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['required' => true])
            ->add('amount', MoneyType::class, ['currency' => 'EUR', 'required' => true])
            ->add('description', TextType::class, ['required' => false]);
    }
}
