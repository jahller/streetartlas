<?php

namespace Jahller\Bundle\AttachmentBundle\Document\Manager;

use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Gaufrette\Exception\FileNotFound;
use Jahller\Bundle\AttachmentBundle\Document\Attachment;
use Jahller\Bundle\AttachmentBundle\Exception\InvalidSizeException;
use Jahller\Bundle\AttachmentBundle\Service\AttachmentService;

class AttachmentManager
{
    protected $filesystemMap;
    protected $attachmentService;
    protected $sizes;

    public function __construct(FilesystemMap $filesystemMap, AttachmentService $attachmentService, array $sizes)
    {
        $this->filesystemMap = $filesystemMap;
        $this->attachmentService = $attachmentService;
        $this->sizes = $sizes;
    }

    public function write(Attachment $attachment, $filesystemKey)
    {
        if (null === $attachment->getPath()) {
            throw new \RuntimeException('No path defined. Did you process the file calling Attachment::processFile() before persisting?');
        }

        $filesystem = $this->filesystemMap->get($filesystemKey);

        try {
            $filesystem->write($attachment->getPath(), file_get_contents($attachment->getFile()->getPathname()), true);
        } catch (\RuntimeException $e) {
            // @ToDo What can we do if writing the file fails?
            throw $e;
        }

        $attachment->setFile(null);

        if (null !== $attachment->getReplacedPath()) {
            $this->deleteByPath($attachment->getReplacedPath(), $filesystemKey);
            $attachment->resetReplacedPath();
        }
    }

    public function read(Attachment $attachment, $filesystemKey)
    {
        $filesystem = $this->filesystemMap->get($filesystemKey);

        return $filesystem->read($attachment->getPath());
    }

    public function copy(Attachment $old, Attachment $new, $filesystemKey)
    {
        $filesystem = $this->filesystemMap->get($filesystemKey);

        try {
            $filesystem->write($new->getPath(), $this->read($old, $filesystemKey), true);
        } catch (\RuntimeException $e) {
            // @ToDo What can we do if writing the file fails?
        }
    }

    public function delete(Attachment $attachment, $filesystemKey)
    {
        $this->deleteByPath($attachment->getPath(), $filesystemKey);

        foreach ($this->sizes as $key => $size) {
            $tmp = preg_split('/\./', $attachment->getPath());
            $uniqueFileName = $tmp[0];
            $extension = $tmp[1];
            $filePath = sprintf('%s.%s.png', $uniqueFileName, $key, $extension);

            $this->deleteByPath($filePath, $filesystemKey);
        }
    }

    private function deleteByPath($path, $filesystemKey)
    {
        $filesystem = $this->filesystemMap->get($filesystemKey);
        try {
            $filesystem->delete($path);
        } catch (FileNotFound $e) {
            // It's okay to ignore that a non-existing file could not be deleted
        }
    }

    public function getPreview(Attachment $attachment, $size)
    {
        try {
            return $this->generatePreview($attachment, $size);
        } catch (InvalidSizeException $e) {
            return $this->getPreview($attachment, 'icon');
        } catch (\RuntimeException $e) {
            throw $e;
        }
    }

    protected function deliverPreview($path)
    {
        return file_get_contents(sprintf('gaufrette://uploads/%s', $path));
    }

    /**
     * Deliver or generate preview file
     *
     * @param Attachment $attachment
     * @param $size
     * @return string
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \Jahller\Bundle\AttachmentBundle\Exception\InvalidSizeException
     */
    public function generatePreview(Attachment $attachment, $size)
    {
        if ($this->isSizeValid($size)) {

            $newFilename = sprintf('%s.%s.png', $attachment->getFileName(), $size);
            $sizeDimensions = $this->sizes[$size];
            $filePath = sprintf('gaufrette://uploads/%s', $attachment->getPreviewPath());
            $filesystem = $this->filesystemMap->get('uploads');

            /* If file already exists deliver it */
            if (file_exists($newFilename)) {
                return $this->deliverPreview($newFilename);
            }

            /* If preview file doesn't exist generate it */
            try {
                $resizedFile = $this->attachmentService->resize($filePath, $sizeDimensions);
                $filesystem->write($newFilename, $resizedFile, true);

                return $this->deliverPreview($newFilename);
            } catch (\RuntimeException $e) {
                // @ToDo What can we do if writing the file fails?
                throw $e;
            }

        }

        throw new InvalidSizeException();
    }

    public function isSizeValid($size)
    {
        if (array_key_exists($size, $this->sizes)) {
            return true;
        }

        return false;
    }

}