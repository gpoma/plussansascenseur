<?php

namespace AppBundle\Type;

use AppBundle\Document\Signalement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SignalementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etage', TextType::class)
            ->add('usage', ChoiceType::class, array('choices' => array_flip(Signalement::$usageList), 'choices_as_values' => true))
            ->add('etageAtteint', RadioType::class)
            ->add('duree', TextType::class)
            ->add('commentaire', TextareaType::class)
            ->add('abonnement', CheckboxType::class)
            ->add('pseudo', TextType::class)
            ->add('email', EmailType::class)
            ->add('telephone', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Signalement::class,
        ]);
    }
}
