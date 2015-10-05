<?php

namespace Jahller\Bundle\ArtlasBundle\Listener;

use Doctrine\ODM\MongoDB\MongoDBException;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Jahller\Bundle\ArtlasBundle\Event\PieceEvents;
use Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager;
use Jahller\Bundle\ArtlasBundle\Event\PieceAddAttachmentEvent;
use Jahller\Bundle\ArtlasBundle\Event\PieceDeleteAttachmentEvent;
use Jahller\Bundle\AttachmentBundle\Document\Manager\AttachmentManager;

class AttachmentListener implements EventSubscriberInterface
{
    protected $attachmentManager;
    protected $pieceManager;

    public function __construct(AttachmentManager $attachmentManager, PieceManager $pieceManager)
    {
        $this->attachmentManager = $attachmentManager;
        $this->pieceManager = $pieceManager;
    }

    /**
     * On pre add event write attachment file into file system
     *
     * @param PieceAddAttachmentEvent $event
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function onPreAddAttachment(PieceAddAttachmentEvent $event)
    {
        $attachment = $event->getAttachment();

        try {
            $this->attachmentManager->write($attachment, 'uploads');
        } catch (FileException $e) {
            /**
             * @todo log exception
             */
            throw new FileException($e->getMessage());
        }
    }

    /**
     * On add event add attachment to tracker item's attachments collection
     *
     * @param PieceAddAttachmentEvent $event
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function onAddAttachment(PieceAddAttachmentEvent $event)
    {
        $piece = $event->getPiece();
        $attachment = $event->getAttachment();

        try {
            $piece->setAttachment($attachment);
            $this->pieceManager->update($piece);
        } catch (MongoDBException $e) {
            /**
             * @todo log exception
             */
            throw new MongoDBException($e->getMessage());
        }
    }

    /**
     * On delete event remove attachment from tracker item's attachments collection
     *
     * @param PieceDeleteAttachmentEvent $event
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function onDeleteAttachment(PieceDeleteAttachmentEvent $event)
    {
        $piece = $event->getPiece();

        try {
            $piece->setAttachment(null);
            $this->pieceManager->update($piece);
        } catch (MongoDBException $e) {
            /**
             * @todo log exception
             */
            throw new MongoDBException($e->getMessage());
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            PieceEvents::PIECE_PRE_ADD_ATTACHMENT => 'onPreAddAttachment',
            PieceEvents::PIECE_ADD_ATTACHMENT => 'onAddAttachment',
            PieceEvents::PIECE_DELETE_ATTACHMENT => 'onDeleteAttachment',
        );
    }
}
