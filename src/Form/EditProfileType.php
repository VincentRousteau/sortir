<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('pseudo',TextType::class, [
                'required' => true,
                'label' => "Pseudo",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un pseudo'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le pseudo doit contenir au minimum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('prenom',TextType::class, [
                'required' => true,
                'label' => "Prénom",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le prénom doit contenir au minimum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('nom',TextType::class, [
                'required' => true,
                'label' => "Nom",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit contenir au minimum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('telephone',TelType::class, [
                'required' => true,
                'label' => "Téléphone",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre numéro de téléphone mobile'
                    ]),
                    new Length([
                        'max' => 10,
                        'maxMessage' => 'Maximum {{ limit }} caractères, au format 06 ou 07'
                    ]),
                ]
            ])
            ->add('email',TextType::class, [
                'required' => true,
                'label' => "Email",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre adresse email'
                    ])
                ]
            ])
            ->add('campus',EntityType::class, [
                'required' => true,
                'label' => 'Choisissez un campus',
                'class' => Campus::class,
                'choice_label' => 'nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un campus'
                    ]),
                ],
            ])
            -> add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Saisir un mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Modifier votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Modifier mon avatar',
                'mapped'=>false,
                'required' =>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
