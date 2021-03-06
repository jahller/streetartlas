<?php

namespace Jahller\Bundle\AttachmentBundle\Form;

use Jahller\Bundle\AttachmentBundle\Form\EventListener\ImageTypeSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new ImageTypeSubscriber());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Jahller\Bundle\AttachmentBundle\Document\Image',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'image';
    }
}