<?php

namespace Jahller\Bundle\ArtlasBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\Type;

/**
 * @MongoDB\Document
 * @ExclusionPolicy("ALL")
 */
class Piece
{
    /**
     * @MongoDB\Id
     * @Expose
     * @Type("string")
     */
    protected $id;

    /**
     * @MongoDB\Boolean
     * @Expose
     * @Type("boolean")
     */
    protected $active;

    /**
     * @MongoDB\EmbedMany(targetDocument="Jahller\Bundle\ArtlasBundle\Document\Tag")
     * @Expose
     * @Type("ArrayCollection<Jahller\Bundle\ArtlasBundle\Document\Tag>")
     */
    protected $tags;

    /**
     * @MongoDB\EmbedOne(targetDocument="Jahller\Bundle\AttachmentBundle\Document\Image")
     * @Expose
     * @Type("Jahller\Bundle\AttachmentBundle\Document\Image")
     */
    protected $image;

    /**
     * Dummy variable to handle file upload

     * @Assert\Image(
     *   maxSize = "50M"
     * )
     */
    protected $imageFile;

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
     * @param mixed $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Toggle active state
     */
    public function toggleActive()
    {
        if ($this->active) {
            $this->setActive(false);
        } else {
            $this->setActive(true);
        }
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->active;
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
     * @param Image $image
     * @return $this
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

}