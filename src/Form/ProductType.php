<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 4]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price',
                'scale' => 2,
                'attr' => ['class' => 'form-control', 'step' => '0.01']
            ])
            ->add('image', FileType::class, [
                'label' => 'Product Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPEG, PNG, GIF, WebP)',
                    ])
                ],
                'attr' => ['class' => 'form-control', 'accept' => 'image/jpeg,image/png,image/gif,image/webp']
            ])
            ->add('category', EntityType::class, [
                'class' => 'App\Entity\Category',
                'choice_label' => 'name',
                'label' => 'Category',
                'attr' => ['class' => 'form-control']
            ])
            ->add('stockQuantity', NumberType::class, [
                'label' => 'Stock Quantity',
                'attr' => ['class' => 'form-control', 'min' => 0]
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Active (visible to customers)',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
