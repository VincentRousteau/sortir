<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ProfileType extends AbstractType
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
                        'min' => 6,
                        'minMessage' => 'Le nom doit contenir au minimum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('prenom',TextType::class, [
                'required' => true,
                'label' => "Prénom",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom'
                    ])
                ]
            ])
            ->add('nom',TextType::class, [
                'required' => true,
                'label' => "Nom",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom'
                    ])
                ]
            ])
            ->add('telephone',NumberType::class, [
                'required' => true,
                'label' => "Téléphone",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre numéro de téléphone mobile'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Minimum {{ limit }} caractères, au format 06 ou 07'
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
            ->add('password',PasswordType::class, [
                'required' => true,
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un mot de passe'
                    ])
                ]
            ])
            ->add('password',PasswordType::class, [
                'required' => true,
                'label' => 'Confirmation',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez confirmer votre mot de passe'
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
                    ])
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Ajouter votre photo',
                'required'=>false
            ])

            ->add('enregistrer',SubmitType::class)
            ->add('annuler',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
