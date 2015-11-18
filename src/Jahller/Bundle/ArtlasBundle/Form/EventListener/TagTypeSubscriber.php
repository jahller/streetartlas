<?php

namespace Jahller\Bundle\ArtlasBundle\Form\EventListener;

use Jahller\Bundle\AttachmentBundle\Form\ImageType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TagTypeSubscriber implements EventSubscriberInterface
{
    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $tag = $event->getData();
        $form = $event->getForm();

        if ($tag && null !== $tag->getId()) {
            $form
                ->add('id')
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