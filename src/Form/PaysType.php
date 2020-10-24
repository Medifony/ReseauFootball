<?php

namespace App\Form;

use App\Entity\Pays;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PaysType extends AbstractType
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
            ->add('nomFr', TextType::class, $this->getConfiguration("Nom français", "Nom français du pays"))
            ->add('nomEn', TextType::class, $this->getConfiguration("Nom anglais", "Nom anglais du pays"))
            ->add('code', IntegerType::class, $this->getConfiguration("Code numérique", "Code numérique du pays"))
            ->add('alpha2', TextType::class, $this->getConfiguration("Code alpha2", "Code alpha2 du pays"))
            ->add('alpha3', TextType::class, $this->getConfiguration("Code alpha3", "Code alpha3 du pays"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pays::class,
        ]);
    }
}
