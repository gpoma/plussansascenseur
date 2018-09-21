<?php

namespace AppBundle\Type;

use AppBundle\Document\Signalement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
            ->add('usage', ChoiceType::class, array('choices' => array_flip(Signalement::$usageList), 'choices_as_values' => true, 'label' => "Pourquoi utilisez-vous cet ascenseur :"))
            ->add('etage', NumberType::class, array('required' => false, 'label' => "Quelle étage souhaitez-vous atteindre :", "attr" => array("placeholder" => "Numéro de l'étage")))
            ->add('etageAtteint', ChoiceType::class, array('choices' => $this->getChoicesEtageAtteint(), 'choices_as_values' => true, 'label' => "Avez-vous pu le rejoindre :"))
            ->add('duree', TextType::class, array('required' => false, 'label' => 'Combien de temps avez-vous mis :', "attr" => array("placeholder" => "Par exemple : 30 min, 1h")))
            ->add('commentaire', TextareaType::class, array('required' => false, 'label' => "Dites-nous pourquoi :"))
            ->add('pseudo', TextType::class, array('required' => false, 'label' => "Votre nom / pseudo :"))
            ->add('abonnement', CheckboxType::class, array('required' => false, 'label' => "Étre tenu informé-e"))
            ->add('email', EmailType::class, array('required' => false, 'label' => "Email :"))
            ->add('telephone', TextType::class, array('required' => false, 'label' => "Téléphone :"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Signalement::class,
        ]);
    }

    public function getChoicesEtageAtteint() {

        return array("Oui, j'ai pu le rejoindre" => true,
                     "Non, je n'ai pas pu le rejoindre" => false);
    }
}
