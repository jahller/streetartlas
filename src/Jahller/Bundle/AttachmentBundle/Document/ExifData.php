<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class ExifData
 * @package Jahller\Bundle\AttachmentBundle\Document
 *
 * @MongoDB\EmbeddedDocument
 */
class ExifData
{
    /**
     * @MongoDB\String
     */
    protected $latitude;

    /**
     * @MongoDB\String
     */
    protected $longitude;

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

}