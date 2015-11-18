<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @MongoDB\EmbeddedDocument
 * @ExclusionPolicy("all")
 */
class Image
{
    /**
     * @MongoDB\Id
     * @Expose
     * @Type("string")
     */
    protected $id;

    /**
     * @var File  - not a persistent field!
     * @Assert\File(
     *   maxSize="50M"
     * )
     */
    protected $file;

    /**
     * @MongoDB\String
     * @Expose
     * @Type("string")
     */
    protected $path;

    /**
     * @MongoDB\String
     * @Expose
     * @Type("string")
     */
    protected $name;

    /**
     * @MongoDB\Int
     * @Expose
     * @Type("integer")
     */
    protected $size;

    /**
     * @MongoDB\String
     * @Expose
     * @Type("string")
     */
    protected $extension;

    /**
     * @MongoDB\String
     * @Expose
     * @Type("string")
     */
    protected $mimeType;

    /**
     * @MongoDB\EmbedOne(targetDocument="Jahller\Bundle\AttachmentBundle\Document\ExifData")
     * @Expose
     * @Type("Jahller\Bundle\AttachmentBundle\Document\ExifData")
     */
    protected $exifData;

    protected $replacedPath = null;

    /**
     * @Assert\Type("bool")
     */
    protected $removalFlag = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getFileName()
    {
        $parts = explode('.', $this->getPath());
        return $parts[0];
    }
    /**
     * @param mixed $extension
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $mimeType
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $removalFlag
     */
    public function setRemovalFlag($removalFlag)
    {
        $this->removalFlag = $removalFlag;
    }

    /**
     * @return mixed
     */
    public function getRemovalFlag()
    {
        return $this->removalFlag;
    }

    /**
     * @param null $replacedPath
     */
    public function setReplacedPath($replacedPath)
    {
        $this->replacedPath = $replacedPath;
    }

    /**
     * @return null
     */
    public function getReplacedPath()
    {
        return $this->replacedPath;
    }

    /**
     * @param mixed $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param ExifData $exifData
     * @return $this
     */
    public function setExifData(ExifData $exifData)
    {
        $this->exifData = $exifData;

        return $this;
    }

    /**
     * @return ExifData
     */
    public function getExifData()
    {
        return $this->exifData;
    }

    public function resetReplacedPath()
    {
        $this->replacedPath = null;

        return $this;
    }

    /**
     * @param mixed $file
     */
    public function processFile($file)
    {
        $this->file = $file;

        if (null === $this->file) {
            return;
        }

        if ($this->file instanceof UploadedFile) {
            $this->name = $this->file->getClientOriginalName();
        }
        if ($this->file instanceof File) {
            $this->name = $this->getName();
        }

        $this->replacedPath = $this->path;
        $this->size = $this->file->getSize();
        $this->extension = $this->file->getClientOriginalExtension();
        $this->mimeType = $this->file->getMimeType();
        $this->path = $this->generatePath();
    }

    private function generatePath()
    {
        return sprintf('%s.%s', sha1(uniqid(mt_rand(), true)), $this->getExtension());
    }

    public function hasFile()
    {
        return null !== $this->file;
    }

    public function getPreviewPath()
    {
        return $this->getPath();
    }

}