<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus',
                'choice_label' => 'nom',
                'class'=> Campus::class
            ])
            ->add('recherche', TextType::class, [
                'mapped'=>false,
                'label' => 'Recherche',
                'attr' => array(
                    'placeholder' => 'Mot clé'
                ),
                'required' => false
            ])
            ->add('dateDebut', DateType::class, [
                'mapped'=>false,
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateType::class, [
                'mapped'=>false,
                'widget' => 'single_text'
            ])
            ->add('sortiesOrganisees', CheckboxType::class, [
                'mapped'=>false,
                'label' => 'Je suis l\'organisateur',
                'required' => false
            ])
            ->add('sortiesInscrit', CheckboxType::class, [
                'mapped'=>false,
                'label' => 'Je suis inscrit/e',
                'required' => false
            ])
            ->add('sortiesNonInscrit', CheckboxType::class, [
                'mapped'=>false,
                'label' => 'Je ne suis pas inscrit/e',
                'required' => false

            ])
            ->add('sortiesPassees', CheckboxType::class, [
                'mapped'=>false,
                'label' => 'Sorties passes',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Rechercher'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}