<?php

namespace App\Form;

use App\Entity\Campus;
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
                'label' => 'Le nom de la sortie contient :',
                'required' => false
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre le'
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'et le'
            ])
            ->add('sortiesOrganisees', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('sortiesInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false
            ])
            ->add('sortiesNonInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false

            ])
            ->add('sortiesPasses', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Rechercher'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EntiteFormulaire::class,
        ]);
    }
}