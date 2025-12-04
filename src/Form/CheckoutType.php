<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('payment_method', ChoiceType::class, [
                'label' => 'Payment Method',
                'choices' => [
                    'Credit Card' => 'credit_card',
                    'PayPal' => 'paypal',
                    'Bank Transfer' => 'bank_transfer',
                ],
                'expanded' => true,
                'multiple' => false,
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Place Order',
                'attr' => ['class' => 'btn btn-primary btn-lg w-100'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
