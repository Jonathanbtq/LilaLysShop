<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AdresseFormType;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use PHPUnit\Framework\Constraint\Callback;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AccountModifFormType extends AbstractType
{
    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => true,
            'current_user_id' => null, // Ajouter l'option pour l'ID de l'utilisateur actuel
        ]);

        // Définir une validation personnalisée pour l'e-mail
        $resolver->setAllowedTypes('current_user_id', ['null', 'int']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone'
            ])
            ->add('adresse', AdresseFormType::class, [
                'label' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirm'
            ])
        ;
    }

    public function validateEmail($value, ExecutionContextInterface $context)
    {
        $submittedEmail = $value;

        // Accédez à l'entité User actuelle depuis le formulaire
        $user = $context->getObject();
        $data = $user->getData();
        // Vérifiez si l'adresse e-mail a été modifiée
        if ($submittedEmail !== $data) {
            $existingUser = $this->userRepo->findOneByEmail($submittedEmail);

            if ($existingUser) {
                $context->buildViolation('There is already an account with this email.')
                    ->atPath('email')
                    ->addViolation();
            }
        }
    }
}
