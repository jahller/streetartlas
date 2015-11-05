<?php

namespace Jahller\Bundle\ArtlasBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @MongoDB\EmbeddedDocument
 * @ExclusionPolicy("ALL")
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
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

}