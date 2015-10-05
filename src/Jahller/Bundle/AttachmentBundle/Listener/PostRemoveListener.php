<?php

namespace Jahller\Bundle\AttachmentBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Jahller\Bundle\AttachmentBundle\Document\Attachment;
use Jahller\Bundle\AttachmentBundle\Document\Manager\AttachmentManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostRemoveListener
{
    protected $attachmentManager;
    protected $attachmentSizes;

    public function __construct(AttachmentManager $attachmentManager)
    {
        $this->attachmentManager = $attachmentManager;
    }

    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        if ($eventArgs->getDocument() instanceof Attachment) {

            /* @var Attachment $attachment */
            $attachment = $eventArgs->getDocument();

            try {
                $this->attachmentManager->delete($attachment, 'uploads');
            } catch (FileException $e) {
                /**
                 * @todo log exception
                 */
            }
        }
    }
}