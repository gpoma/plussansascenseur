<?php

namespace AppBundle\Type;

use AppBundle\Document\Ascenseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

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
            ->add('dateConstruction', DateType::class, array('required' => false, 'label' => "Date de construction :", 'widget' => 'single_text', 'html5' => true))
            ->add('dateRenovation', DateType::class, array('required' => false, 'label' => "Date de rénovation :", 'widget' => 'single_text', 'html5' => true))
            ->add('etageMin', IntegerType::class, array('required' => false, 'label' => "Étage le plus bas :"))
            ->add('etageMax', IntegerType::class, array('required' => false, 'label' => "Étage le plus haut :"))
            ->add('bailleurNom', TextType::class, array('required' => false, 'label' => "Nom du bailleur :"))
            ->add('bailleurContact', TextType::class, array('required' => false, 'label' => "Contact du bailleur :"))
            ->add('syndicNom', TextType::class, array('required' => false, 'label' => "Nom du syndic :"))
            ->add('syndicContact', TextType::class, array('required' => false, 'label' => "Contact du syndic :"))
            ->add('ascensoristeNom', TextType::class, array('required' => false, 'label' => "Nom de l'ascensoriste :"))
            ->add('ascensoristeContact', TextType::class, array('required' => false, 'label' => "Contact de l'ascensoriste :"))
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
