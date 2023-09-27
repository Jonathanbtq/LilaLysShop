<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAddTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('visible_image', CheckboxType::class, [
                'label' => 'Image visible',
                'required' => false
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix'
            ])
            ->add('list_price', TextType::class, [
                'label' => 'List_price'
            ])
            ->add('price_extra', TextType::class, [
                'label' => 'price_extra'
            ])
            ->add('has_discounted_price', CheckboxType::class, [
                'label' => 'Promo?',
                'required' => false
            ])
            ->add('stock_quantity', TextType::class, [
                'label' => 'Stock'
            ])
            ->add('product_type', TextType::class, [
                'label' => 'Product_type'
            ])
            ->add('product_image', FileType::class, [
                'mapped' => false,
                'label' => 'Image'
            ])
            ->add('productImgs', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            // ->add('productImgs', ProductImgFormType::class, [
            //     'label' => false,
            //     'data_class' => null
            // ])
            ->add('category', EntityType::class, [
                'label' => 'Nom',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
