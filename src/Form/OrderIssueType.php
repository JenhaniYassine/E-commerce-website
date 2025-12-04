<?php

namespace App\Form;

use App\Entity\OrderIssue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderIssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('order', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, [
                'class' => \App\Entity\Order::class,
                'choice_label' => function($order) {
                    return 'Order #' . $order->getId() . ' - ' . $order->getCreatedAt()->format('M d, Y');
                },
                'label' => 'Select Order',
                'placeholder' => 'Choose an order...',
                'query_builder' => function(\App\Repository\OrderRepository $repo) use ($options) {
                    return $repo->createQueryBuilder('o')
                        ->where('o.user = :user')
                        ->setParameter('user', $options['user'])
                        ->orderBy('o.createdAt', 'DESC');
                },
                'attr' => ['class' => 'form-control']
            ])
            ->add('issueType', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
                'choices' => [
                    'I didn\'t receive my order' => 'not_received',
                    'I want to return my order' => 'return_request',
                    'Wrong item received' => 'wrong_item',
                    'Damaged item' => 'damaged_item',
                    'Other issue' => 'other'
                ],
                'label' => 'Issue Type',
                'placeholder' => 'Select the type of issue',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Please provide details about your issue...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderIssue::class,
        ]);

        $resolver->setRequired(['user']);
        $resolver->setAllowedTypes('user', \App\Entity\User::class);
    }
}
