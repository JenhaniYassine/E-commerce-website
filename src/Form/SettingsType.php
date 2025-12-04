<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('logo', FileType::class, [
                'label' => 'Logo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('primaryColor', ColorType::class, [
                'label' => 'Primary Color',
                'required' => false,
            ])
            ->add('secondaryColor', ColorType::class, [
                'label' => 'Secondary Color',
                'required' => false,
            ])
            ->add('brandingText', TextareaType::class, [
                'label' => 'Branding Text',
                'required' => false,
            ])
            ->add('paymentGateway', ChoiceType::class, [
                'label' => 'Payment Gateway',
                'choices' => [
                    'Stripe' => 'stripe',
                    'PayPal' => 'paypal',
                    'Bank Transfer' => 'bank',
                ],
                'required' => false,
            ])
            ->add('deliveryMethods', ChoiceType::class, [
                'label' => 'Delivery Methods',
                'choices' => [
                    'Standard Shipping' => 'standard',
                    'Express Shipping' => 'express',
                    'Pickup' => 'pickup',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('adminEmail', EmailType::class, [
                'label' => 'Admin Email',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}
