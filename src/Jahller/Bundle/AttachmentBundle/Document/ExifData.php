<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * Class ExifData
 * @package Jahller\Bundle\AttachmentBundle\Document
 *
 * @MongoDB\EmbeddedDocument
 * @ExclusionPolicy("all")
 */
class ExifData
{
    /**
     * @MongoDB\String
     * @Expose
     * @Type("string")
     */
    protected $latitude;

    /**
     * @MongoDB\String
     * @Expose
     * @Type("string")
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