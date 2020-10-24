<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Sujet;
use App\Entity\Reponse;
use App\Entity\Signalement;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminSignalementType extends AbstractType
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
            ->add('raison', TextType::class, $this->getConfiguration("Raison", "Votre raison ..."))
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'En attente' => 0,
                    'Modéré' => 1,
                ],
            ])
            ->add('sujets', EntityType::class, [
                'class' => Sujet::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->join('s.championnats', 'champ')
                        ->select('s')
                        ->where('champ IS NOT NULL')
                        ->orderBy('s.id', 'ASC');
                },
                'choice_label' => function ($label) {
                    return $label->getId() . ' - ' . $label->getChampionnats()->getNom() . ' - '. $label->getTitre();
                },
                'required' => false
            ])
            ->add('reponses', EntityType::class, [
                'class' => Reponse::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->join('r.sujets', 'sujet')
                        ->select('r')
                        ->where('sujet.championnats IS NOT NULL')
                        ->orderBy('r.id', 'ASC');
                },
                'choice_label' => function ($label) {
                    return 'Reponse: ' . $label->getId() . ' pour le sujet "' . $label->getSujets()->getTitre() . '"';
                },
                'required' => false
            ])
            ->add('users', EntityType::class, [
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
            'data_class' => Signalement::class,
        ]);
    }
}
