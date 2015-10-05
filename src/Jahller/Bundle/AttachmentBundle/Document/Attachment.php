<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Attachment
 * @package Onemedia\AttachmentBundle\Document
 */
abstract class Attachment implements AttachmentInterface
{
    /**
     * @MongoDB\Id(strategy="auto")
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
     */
    protected $path;

    /**
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\Int
     */
    protected $size;

    /**
     * @MongoDB\String
     */
    protected $extension;

    /**
     * @MongoDB\String
     */
    protected $mimeType;

    protected $replacedPath = null;

    /**
     * @Assert\Type("bool")
     */
    protected $removalFlag = false;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
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
     */
    public function setName($name)
    {
        $this->name = $name;
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
     */
    public function setPath($path)
    {
        $this->path = $path;
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
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
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
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
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
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    public function resetReplacedPath()
    {
        $this->replacedPath = null;
    }

    /**
     * @param UploadedFile $file
     */
    public function processFile(UploadedFile $file)
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

    abstract public function getPreviewPath();
}