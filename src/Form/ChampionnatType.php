<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\Championnat;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ChampionnatType extends AbstractType
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
            ->add('nom', TextType::class, $this->getConfiguration("Nom", "Nom du championnat"))
            ->add('logo', TextType::class, $this->getConfiguration("Logo", "Chemin du logo"))
            ->add('navbar', TextType::class, $this->getConfiguration("Navbar", "Navbar du championnat"))
            ->add('slug', TextType::class, $this->getConfiguration("Slug", "Slug du championnat"))
            ->add('payss', EntityType::class, [
                'class' => Pays::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nomFr', 'ASC');
                },
                'choice_label' => 'nomFr',
            ])
            ->add('apiId', IntegerType::class, $this->getConfiguration("Api", "Id du championnat dans l'api...", [
                'required' => false]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Championnat::class,
        ]);
    }
}
