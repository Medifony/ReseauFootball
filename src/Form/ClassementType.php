<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Classement;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ClassementType extends AbstractType
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
            ->add('place', IntegerType::class, $this->getConfiguration("Place", "Place du club"))
            ->add('total', IntegerType::class, $this->getConfiguration("Total", "Nombre de matchs joués"))
            ->add('victoires', IntegerType::class, $this->getConfiguration("Victoires", "Nombre de victoires"))
            ->add('nuls', IntegerType::class, $this->getConfiguration("Nuls", "Nombre de matchs nuls"))
            ->add('defaites', IntegerType::class, $this->getConfiguration("Défaites", "Nombre de défaites"))
            ->add('points', IntegerType::class, $this->getConfiguration("Points", "Nombre de points"))
            ->add('buts', IntegerType::class, $this->getConfiguration("Buts", "Nombre de buts"))
            ->add('encaisse', IntegerType::class, $this->getConfiguration("Buts encaissés", "Nombre de buts encaissés"))
            ->add('clubs', EntityType::class, [
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
            'data_class' => Classement::class,
        ]);
    }
}
