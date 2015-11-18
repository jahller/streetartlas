<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

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
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $latitude;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $longitude;

    /**
     * @param mixed $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
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
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

}