<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse_fact')
            ->add('telephone')
            ->add('fact_mail')
            ->add('work_modele')
            ->add('description')
            ->add('total_discount')
            ->add('nb_product')
            ->add('ship_price')
            ->add('price_bfr_taxe')
            ->add('sous_total')
            ->add('sous_total_taxe')
            ->add('total_price')
            ->add('submit', SubmitType::class, [
                'label' => 'Validez'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
