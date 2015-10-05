<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Image
 * @package Onemedia\AttachmentBundle\Document
 *
 * @MongoDB\EmbeddedDocument
 */
class Image extends Attachment
{
    public function processFile()
    {
        parent::processFile();
    }

    public function getPreviewPath()
    {
        return $this->getPath();
    }
}