<?php

namespace App\Form;

use App\Entity\DemandeAmi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EditAmisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amiStatut', ChoiceType::class, [
                'choices'  => [
                    'En attente' => 0,
                    'Acceptée' => 1,
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DemandeAmi::class,
        ]);
    }
}
