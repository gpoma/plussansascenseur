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
            ->add('etage', TextType::class, array('required' => false))
            ->add('usage', ChoiceType::class, array('choices' => array_flip(Signalement::$usageList), 'choices_as_values' => true))
            ->add('etageAtteint', ChoiceType::class, array('choices' => $this->getChoicesEtageAtteint(), 'choices_as_values' => true, 'expanded' => true, 'required' => false))
            ->add('duree', TextType::class, array('required' => false))
            ->add('commentaire', TextareaType::class, array('required' => false))
            ->add('abonnement', CheckboxType::class, array('required' => false))
            ->add('pseudo', TextType::class, array('required' => false))
            ->add('email', EmailType::class, array('required' => false))
            ->add('telephone', TextType::class, array('required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Signalement::class,
        ]);
    }

    public function getChoicesEtageAtteint() {

        return array("J'ai pu rejoindre cet étage" => "",
                     "Je n'ai pas pu rejoindre cet étage" => "1");
    }
}
