<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    /**
     * @MongoDB\EmbedOne(targetDocument="Jahller\Bundle\AttachmentBundle\Document\ExifData")
     */
    protected $exifData;

    public function processFile(UploadedFile $file)
    {
        parent::processFile($file);
    }

    public function getPreviewPath()
    {
        return $this->getPath();
    }

    /**
     * @param mixed $exifData
     * @return $this
     */
    public function setExifData($exifData)
    {
        $this->exifData = $exifData;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExifData()
    {
        return $this->exifData;
    }

}