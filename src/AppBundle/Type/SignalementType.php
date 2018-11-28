<?php

namespace AppBundle\Type;

use AppBundle\Document\Signalement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SignalementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usage', ChoiceType::class, array('choices' => array_flip(Signalement::$usageList), 'choices_as_values' => true, 'required' => false, 'label' => "Pourquoi comptiez-vous utiliser cet ascenseur :"))
            ->add('etage', IntegerType::class, array('required' => false, 'label' => "Quelle étage souhaitez-vous atteindre :", "attr" => array("placeholder" => "", "type" => "number" )))
            ->add('etageAtteint', ChoiceType::class, array('choices' => $this->getChoicesEtageAtteint(), 'choices_as_values' => true, 'required' => false, 'label' => "Avez-vous pu rejoindre votre étage :"))
            ->add('duree', TextType::class, array('required' => false, 'label' => 'Combien de temps avez-vous mis :', "attr" => array("placeholder" => "")))
            ->add('commentaire', TextareaType::class, array('required' => false, 'label' => "Expliquez la situation :"))
            ->add('pseudo', TextType::class, array('required' => false, 'label' => "Votre nom / pseudo :"))
            ->add('abonnement', CheckboxType::class, array('required' => false, 'label' => "Être tenu-e informé-e"))
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
