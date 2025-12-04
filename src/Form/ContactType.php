<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, [
                'label' => 'First Name',
                'attr' => ['placeholder' => 'Enter your first name']
            ])
            ->add('lastName', null, [
                'label' => 'Last Name',
                'attr' => ['placeholder' => 'Enter your last name']
            ])
            ->add('email', null, [
                'label' => 'Email Address',
                'attr' => ['placeholder' => 'Enter your email address']
            ])
            ->add('subject', null, [
                'label' => 'Subject',
                'attr' => ['placeholder' => 'What is this about?']
            ])
            ->add('message', null, [
                'label' => 'Message',
                'attr' => ['placeholder' => 'Tell us how we can help you...', 'rows' => 5]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
