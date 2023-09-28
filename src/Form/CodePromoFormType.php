<?php

namespace App\Form;

use App\Entity\CodePromo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodePromoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'code promo'
            ])
            ->add('pourcentage_reduction', TextType::class, [
                'label' => 'Pourcentage de réduction'
            ])
            ->add('expiration_date', DateType::class, [
                'label' => 'Date d\'expiration',
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CodePromo::class,
        ]);
    }
}
