<?php

namespace AppBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;



class PhotoType extends AbstractType {


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('imageFile', VichFileType::class, array(
            'required' => true,
            'allow_delete' => false,
            'label' => 'Choisir un document (.jpg, .png, .pdf)',
        ))->add('lat', HiddenType::class, array(
            'data' => 0,
            'mapped' => false
        ))->add('lon', HiddenType::class, array(
            'data' => 0,
            'mapped' => false
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'photos';
    }

}
