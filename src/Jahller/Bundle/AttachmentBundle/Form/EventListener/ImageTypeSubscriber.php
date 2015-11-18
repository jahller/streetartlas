<?php

namespace Jahller\Bundle\AttachmentBundle\Form\EventListener;

use Jahller\Bundle\AttachmentBundle\Form\ExifDataType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageTypeSubscriber implements EventSubscriberInterface
{
    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $image = $event->getData();
        $form = $event->getForm();

        if ($image && null !== $image->getId()) {
            $form
                ->add('id')
                ->add('path')
                ->add('name')
                ->add('size')
                ->add('extension')
                ->add('mimeType')
                ->add('exifData', new ExifDataType())
            ;
        } else {
            $form
                ->add('file')
            ;
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }
}