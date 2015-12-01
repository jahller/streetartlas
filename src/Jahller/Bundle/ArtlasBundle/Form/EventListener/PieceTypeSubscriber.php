<?php

namespace Jahller\Bundle\ArtlasBundle\Form\EventListener;

use Jahller\Bundle\AttachmentBundle\Form\ImageType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PieceTypeSubscriber implements EventSubscriberInterface
{
    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $piece = $event->getData();
        $form = $event->getForm();

        if ($piece && null !== $piece->getId()) {
            $form
                ->add('id')
                ->add('active')
                ->add('image', new ImageType())
            ;
        } else {
            $form
                ->add('imageFile', 'file', array(
                    'required' => true,
                ))
                ->add('save', 'submit', array(
                    'label' => 'Create Piece'
                ))
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