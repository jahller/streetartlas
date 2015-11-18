<?php

namespace Jahller\Bundle\ArtlasBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @MongoDB\EmbeddedDocument
 * @ExclusionPolicy("all")
 */
class Tag
{
    /**
     * @MongoDB\Id
     * @Expose
     * @Type("string")
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Assert\NotBlank()
     * @Expose
     * @Type("string")
     */
    protected $title;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

}