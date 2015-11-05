<?php

namespace Jahller\Bundle\ArtlasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => true,
            ))
            ->add('latitude', 'text', array(
                'required' => true,
            ))
            ->add('longitude', 'text', array(
                'required' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jahller\Bundle\ArtlasBundle\Document\City',
        ));
    }

    public function getName()
    {
        return 'city';
    }

}