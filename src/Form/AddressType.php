<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr' => ['placeholder' => 'Enter your first name']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['placeholder' => 'Enter your last name']
            ])
            ->add('company', TextType::class, [
                'label' => 'Company (Optional)',
                'required' => false,
                'attr' => ['placeholder' => 'Enter company name']
            ])
            ->add('streetAddress', TextType::class, [
                'label' => 'Street Address',
                'attr' => ['placeholder' => 'Enter street address']
            ])
            ->add('apartment', TextType::class, [
                'label' => 'Apartment/Suite (Optional)',
                'required' => false,
                'attr' => ['placeholder' => 'Enter apartment or suite number']
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'attr' => ['placeholder' => 'Enter city']
            ])
            ->add('state', TextType::class, [
                'label' => 'State/Province',
                'attr' => ['placeholder' => 'Enter state or province']
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Postal Code',
                'attr' => ['placeholder' => 'Enter postal code']
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'preferred_choices' => ['US', 'CA', 'GB'],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone Number (Optional)',
                'required' => false,
                'attr' => ['placeholder' => 'Enter phone number']
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Address Type',
                'choices' => [
                    'Billing Address' => 'billing',
                    'Shipping Address' => 'shipping',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('isDefault', CheckboxType::class, [
                'label' => 'Set as default address',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
