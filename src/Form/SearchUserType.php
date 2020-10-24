<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Pays;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchUserType extends AbstractType
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
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'Tapez le pseudo en entier ou en partie de l\'utilisateur recherché...',
                ],
                'required' => false
            ])
            ->add('Clubs', EntityType::class, [
                'label' => 'Club',
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
                },
                'required' => false
            ])
            ->add('Pays', EntityType::class, [
                'label' => 'Pays',
                'class' => Pays::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nomFr', 'ASC');
                },
                'choice_label' => 'nomFr',
                'required' => false
            ])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
