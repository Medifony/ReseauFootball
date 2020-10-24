<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\User;
use App\Entity\Sujet;
use App\Entity\Championnat;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminSujetType extends AbstractType
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
            ->add('titre', TextType::class, $this->getConfiguration("Titre", "Votre titre ..."))
            ->add('details', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Description de votre sujet ...',
                          'class' => 'areasujet',
                          'rows' => 8
                ],
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.pseudo', 'ASC');
                },
                'choice_label' => 'pseudo',
            ])
            ->add('championnats', EntityType::class, [
                'class' => Championnat::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'required' => false
            ])
            ->add('clubs', EntityType::class, [
                'class' => Club::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->join('c.championnats', 'champ')
                        ->select('c')
                        ->where('champ IS NOT NULL')
                        ->orderBy('champ.nom', 'ASC');
                },
                'choice_label' => function ($label) {
                    return $label->getId() . ' - ' . $label->getChampionnats()->getNom() . ' - ' . $label->getNom() ;
                },
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class,
        ]);
    }
}
