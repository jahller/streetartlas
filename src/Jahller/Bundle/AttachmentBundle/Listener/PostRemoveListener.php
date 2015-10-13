<?php

namespace Jahller\Bundle\AttachmentBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Jahller\Bundle\AttachmentBundle\Document\Manager\ImageManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostRemoveListener
{
    protected $imageManager;
    protected $logger;

    public function __construct(ImageManager $imageManager, Logger $logger)
    {
        $this->imageManager = $imageManager;
        $this->logger = $logger;
    }

    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        if ($eventArgs->getDocument() instanceof Image) {

            /* @var Image $image */
            $image = $eventArgs->getDocument();

            try {
                $this->imageManager->delete($image, 'uploads');
            } catch (FileException $e) {
                $this->logger->error('PostRemoveListener: Error while deleting image ' . $image->getFileName() . ' Error: ' . $e->getMessage());
            }
        }
    }
}