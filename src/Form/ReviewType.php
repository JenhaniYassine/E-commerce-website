<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'choices' => [
                    '5 Stars' => 5,
                    '4 Stars' => 4,
                    '3 Stars' => 3,
                    '2 Stars' => 2,
                    '1 Star' => 1,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Rating',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please select a rating.']),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 5,
                        'minMessage' => 'Rating must be at least 1.',
                        'maxMessage' => 'Rating cannot be more than 5.',
                    ]),
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Your Review',
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Share your experience with this product...',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please write a review.']),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 1000,
                        'minMessage' => 'Review must be at least {{ limit }} characters long.',
                        'maxMessage' => 'Review cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
