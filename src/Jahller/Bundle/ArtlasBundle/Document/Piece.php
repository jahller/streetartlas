<?php

namespace Jahller\Bundle\ArtlasBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Jahller\Bundle\AttachmentBundle\Document\Attachment;
use Jahller\Bundle\AttachmentBundle\Document\AttachmentInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * @MongoDB\Document
 */
class Piece
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $title;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Jahller\Bundle\ArtlasBundle\Document\Tag", cascade={"all"})
     */
    protected $tags;

    /**
     * @var
     */
    protected $imageFile;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Jahller\Bundle\AttachmentBundle\Document\Attachment", cascade={"all"})
     */
    protected $attachment;

    /**
     * General constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

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

    /**
     * @param Tag $tag
     * @return $this
     */
    public function addTag(Tag $tag)
    {
        if (!$this->hasTag($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function removeTag(Tag $tag)
    {
        if ($this->hasTag($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     * @return mixed
     */
    public function hasTag(Tag $tag)
    {
        return $this->tags->contains($tag);
    }

    /**
     * @param mixed $imageFile
     * @return $this
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param Attachment $attachment
     * @return $this
     */
    public function setAttachment(Attachment $attachment)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

}