<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Classement;
use App\Entity\Championnat;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ClubType extends AbstractType
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
            ->add('nom', TextType::class, $this->getConfiguration("Nom", "Nom du club ..."))
            ->add('logo', TextType::class, $this->getConfiguration("Logo", "Logo du club ..."))
            ->add('navbar', TextType::class, $this->getConfiguration("Déminutif", "Déminutif du club ..."))
            ->add('slug', TextType::class, $this->getConfiguration("Slug", "Slug du club ..."))
            ->add('championnats', EntityType::class, [
                'class' => Championnat::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ])
            ->add('apiId', IntegerType::class, $this->getConfiguration("Api", "Id du club dans l'api...", [
                'required' => false]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
