<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\User;
use App\Entity\Adresse;
use App\Repository\PaysRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AccountModifFormType extends AbstractType
{
    public function __construct()
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextareaType::class, [
                'label' => 'Name'
            ])
            ->add('email', TextareaType::class, [
                'label' => 'Email'
            ])
            ->add('telephone', TextareaType::class, [
                'label' => 'Téléphone'
            ])
            ->add('adresse', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => 'pays_id.nom', // Affichez la colonne "nom" de la table "pays"
                'label' => 'Pays',
                'placeholder' => 'Sélectionnez un pays', // Texte facultatif pour l'option par défaut
                'multiple' => false, // Pour permettre la sélection d'un seul pays
                'expanded' => false, // Pour afficher un sélecteur de type dropdown
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirm'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
