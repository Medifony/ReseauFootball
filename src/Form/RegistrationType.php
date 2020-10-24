<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Pays;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends AbstractType
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
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => "Photo de profil (format jpg)"
            ])
            ->add('pseudo', TextType::class, $this->getConfiguration("Pseudo", "Votre pseudonyme ..."))
            ->add('prenom', TextType::class, $this->getConfiguration("Prénom", "Votre prénom ..."))
            ->add('nom', TextType::class, $this->getConfiguration("Nom", "Votre nom de famille ..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre adresse email"))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Choisissez un bon mot de passe !"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe"))
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
                    return $label->getChampionnats()->getNom() . ' - ' . $label->getNom() ;
                },
                'required' => false
            ])
            ->add('payss', EntityType::class, [
                'class' => Pays::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nomFr', 'ASC');
                },
                'choice_label' => 'nomFr',
                'required' => false
            ])
            ->add('presentation', TextareaType::class, [
                'label' => 'Présentation',
                'attr' => ['placeholder' => 'Votre présentation ...',
                          'rows' => 3
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
