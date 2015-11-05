<?php

namespace Jahller\Bundle\ArtlasBundle\Form;

use Jahller\Bundle\ArtlasBundle\Form\EventListener\PieceTypeSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags', 'collection', array(
                'type' => new TagType(),
                'allow_add' => true,
                'allow_delete' => true,
            ));

        $builder->addEventSubscriber(new PieceTypeSubscriber());
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