<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('visible_image', TextType::class, [
                'label' => 'Image visible'
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
            ->add('has_discounted_price', TextType::class, [
                'label' => 'Promo?'
            ])
            ->add('stock_quantity', TextType::class, [
                'label' => 'Stock'
            ])
            ->add('product_type', TextType::class, [
                'label' => 'Product_type'
            ])
            ->add('product_image', TextType::class, [
                'label' => 'Image'
            ])
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
