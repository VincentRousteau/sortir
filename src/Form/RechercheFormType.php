<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', ChoiceType::class, [
                'label' => 'Campus'
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus',
                'choice_label' => 'nom',
                'class'=> Campus::class
            ])
            ->add('contient', TextType::class, [
                'label' => 'Le nom de la sortie contient :'
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre le'
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'et le'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}