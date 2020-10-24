<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\DemandeAmi;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DemandeAmiType extends AbstractType
{
    private function getConfiguration($label, $placeholder, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amiStatut', ChoiceType::class, [
                'choices'  => [
                    'En attente' => 0,
                    'Acceptée' => 1,
                ],
            ])
            ->add('userAjout', EntityType::class, [
                'label' => 'User envoie',
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.pseudo', 'ASC');
                },
                'choice_label' => 'pseudo',
            ])
            ->add('userRec', EntityType::class, [
                'label' => 'User reçoit',
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.pseudo', 'ASC');
                },
                'choice_label' => 'pseudo',
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
