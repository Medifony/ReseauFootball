<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Conversation;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MessageType extends AbstractType
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
            ->add('contenu', TextType::class, $this->getConfiguration("Contenu", "Contenu du message ..."))
            ->add('users', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.pseudo', 'ASC');
                },
                'choice_label' => 'pseudo',
            ])
            ->add('conversations', EntityType::class, [
                'class' => Conversation::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.id', 'ASC');
                },
                'choice_label' => function ($label) {
                    $util = '';
                    foreach($label->getParticipants() as $utilis)
                    { 
                        $util .= ' - ' . $utilis->getUsers()->getPseudo();
                    }
                    return $label->getId() .  $util;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
