<?php

namespace App\Form;

use App\Entity\PaymentMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cardType', ChoiceType::class, [
                'label' => 'Card Type',
                'choices' => [
                    'Visa' => 'visa',
                    'MasterCard' => 'mastercard',
                    'American Express' => 'amex',
                    'Discover' => 'discover',
                ],
                'placeholder' => 'Select card type',
            ])
            ->add('cardholderName', TextType::class, [
                'label' => 'Cardholder Name',
                'attr' => ['placeholder' => 'Enter name on card']
            ])
            ->add('lastFourDigits', TextType::class, [
                'label' => 'Last 4 Digits',
                'attr' => [
                    'placeholder' => '1234',
                    'maxlength' => 4,
                    'pattern' => '[0-9]{4}'
                ]
            ])
            ->add('expiryMonth', ChoiceType::class, [
                'label' => 'Expiry Month',
                'choices' => array_combine(range(1, 12), range(1, 12)),
                'placeholder' => 'Month',
            ])
            ->add('expiryYear', ChoiceType::class, [
                'label' => 'Expiry Year',
                'choices' => array_combine(range(date('Y'), date('Y') + 20), range(date('Y'), date('Y') + 20)),
                'placeholder' => 'Year',
            ])
            ->add('isDefault', ChoiceType::class, [
                'label' => 'Set as Default Payment Method',
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentMethod::class,
        ]);
    }
}
