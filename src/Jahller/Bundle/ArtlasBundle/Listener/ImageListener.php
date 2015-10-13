<?php

namespace Jahller\Bundle\ArtlasBundle\Listener;

use Doctrine\ODM\MongoDB\MongoDBException;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Jahller\Bundle\ArtlasBundle\Event\PieceEvents;
use Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager;
use Jahller\Bundle\ArtlasBundle\Event\PieceAddImageEvent;
use Jahller\Bundle\ArtlasBundle\Event\PieceDeleteImageEvent;
use Jahller\Bundle\AttachmentBundle\Document\Manager\ImageManager;

class ImageListener implements EventSubscriberInterface
{
    protected $imageManager;
    protected $pieceManager;

    public function __construct(ImageManager $imageManager, PieceManager $pieceManager)
    {
        $this->imageManager = $imageManager;
        $this->pieceManager = $pieceManager;
    }

    /**
     * On pre add event write image file into file system
     *
     * @param PieceAddImageEvent $event
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function onPreAddImage(PieceAddImageEvent $event)
    {
        $image = $event->getImage();

        try {
            $this->imageManager->write($image, 'uploads');
        } catch (FileException $e) {
            /**
             * @todo log exception
             */
            throw new FileException($e->getMessage());
        }
    }

    /**
     * On add event add image to piece
     *
     * @param PieceAddImageEvent $event
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function onAddImage(PieceAddImageEvent $event)
    {
        $piece = $event->getPiece();
        $image = $event->getImage();

        try {
            $piece->setImage($image);
            $this->pieceManager->update($piece);
        } catch (MongoDBException $e) {
            /**
             * @todo log exception
             */
            throw new MongoDBException($e->getMessage());
        }
    }

    /**
     * On delete event remove image from piece
     *
     * @param PieceDeleteImageEvent $event
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function onDeleteImage(PieceDeleteImageEvent $event)
    {
        $piece = $event->getPiece();

        try {
            $piece->setImage(null);
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
            PieceEvents::PIECE_PRE_ADD_IMAGE => 'onPreAddImage',
            PieceEvents::PIECE_ADD_IMAGE => 'onAddImage',
            PieceEvents::PIECE_DELETE_IMAGE => 'onDeleteImage',
        );
    }
}
