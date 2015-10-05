<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * If an attachment is a PDF file this class is used
 *
 * Class Pdf
 * @package Onemedia\AttachmentBundle\Document
 *
 * @MongoDB\EmbeddedDocument
 */
class Pdf extends Attachment
{
    protected $thumbnailSuffix = 'thumb';

    public function processFile()
    {
        parent::processFile();
    }

    public function getPreviewPath()
    {
        return $this->getPath();
    }

}