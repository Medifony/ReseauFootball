<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Rencontre;
use App\Entity\Championnat;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RencontreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matchDate', DateTimeType::class,[ 'date_format' => 'dd-MM-yyyy',
            'label' => 'Date du début du match' ])
            ->add('championnats', EntityType::class, [
                'class' => Championnat::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ])
            ->add('clubDom', EntityType::class, [
                'label' => 'Club domicile',
                'class' => Club::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->join('c.championnats', 'champ')
                        ->select('c')
                        ->where('champ IS NOT NULL')
                        ->orderBy('c.championnats', 'ASC');
                },
                'choice_label' => function ($label) {
                    return $label->getId() . ' - ' . $label->getChampionnats()->getNom() . ' - ' . $label->getNom() ;
                }
            ])
            ->add('clubExt', EntityType::class, [
                'label' => 'Club extérieur',
                'class' => Club::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->join('c.championnats', 'champ')
                        ->select('c')
                        ->where('champ IS NOT NULL')
                        ->orderBy('c.championnats', 'ASC');
                },
                'choice_label' => function ($label) {
                    return $label->getId() . ' - ' . $label->getChampionnats()->getNom() . ' - ' . $label->getNom() ;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rencontre::class,
        ]);
    }
}
