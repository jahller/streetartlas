<?php

namespace Jahller\Bundle\ArtlasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', 'file', array(
                'required' => true
            ))
            ->add('tags', 'collection', array(
                'type' => new TagType(),
                'allow_add' => true,
                'options' => array()
            ))
            ->add('save', 'submit', array(
                'label' => 'Create Piece'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jahller\Bundle\ArtlasBundle\Document\Piece',
        ));
    }

    public function getName()
    {
        return 'piece';
    }
}