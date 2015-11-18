<?php

namespace Jahller\Bundle\ArtlasBundle\Form;

use Jahller\Bundle\ArtlasBundle\Form\EventListener\TagTypeSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => true
            ));

        $builder->addEventSubscriber(new TagTypeSubscriber());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Jahller\Bundle\ArtlasBundle\Document\Tag',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tag';
    }
}