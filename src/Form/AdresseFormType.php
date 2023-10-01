<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\City;
use App\Entity\Pays;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rue', TextType::class, [
                'label' => 'Rue',
                'attr' => [
                    'name' => 'rue'
                ]
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code Postal'
            ])
            ->add('complement_adrr', TextType::class, [
                'label' => 'Complément d\'adresse'
            ])
            ->add('pays', EntityType::class, [
                'class' => Pays::class,
                'choice_label' => 'nom',
                'label' => 'Pays',
                'placeholder' => 'Sélectionnez un pays',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
