<?php

namespace AppBundle\Type;

use AppBundle\Document\Ascenseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AscenseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse', TextType::class, array('required' => false, 'label' => "Adresse :"))
            ->add('codePostal', TextType::class, array('required' => false, 'label' => "Code postal :"))
            ->add('commune', TextType::class, array('required' => false, 'label' => "Commune :"))
            ->add('emplacement', TextType::class, array('required' => false, 'label' => "Emplacement :"))
            ->add('reference', TextType::class, array('required' => false, 'label' => "Numéro de référence :"))
            ->add('constructeurNom', TextType::class, array('required' => false, 'label' => "Constructeur :"))
            ->add('etageMin', NumberType::class, array('required' => false, 'label' => "Étage le plus bas :"))
            ->add('etageMax', NumberType::class, array('required' => false, 'label' => "Étage le plus haut :"))
            ->add('telephoneDepannage', TextType::class, array('required' => false, 'label' => "N° de téléphone du dépannage :"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ascenseur::class,
        ]);
    }
}
