<?php

namespace Jahller\Bundle\AttachmentBundle\Document\Manager;

use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Gaufrette\Exception\FileNotFound;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Jahller\Bundle\AttachmentBundle\Exception\InvalidSizeException;
use Jahller\Bundle\AttachmentBundle\Service\ImageService;
use Symfony\Bridge\Monolog\Logger;

class ImageManager
{
    protected $filesystemMap;
    protected $imageService;
    protected $sizes;
    protected $logger;

    public function __construct(FilesystemMap $filesystemMap, ImageService $imageService, Logger $logger, array $sizes)
    {
        $this->filesystemMap = $filesystemMap;
        $this->imageService = $imageService;
        $this->logger = $logger;
        $this->sizes = $sizes;
    }

    public function write(Image $image, $filesystemKey)
    {
        if (null === $image->getPath()) {
            throw new \RuntimeException('No path defined. Did you process the file calling Image::processFile() before persisting?');
        }

        $filesystem = $this->filesystemMap->get($filesystemKey);

        try {
            $filesystem->write($image->getPath(), file_get_contents($image->getFile()->getPathname()), true);
        } catch (\RuntimeException $e) {
            $this->logger->error('ImageManager: Writing the file failed for path ' . $image->getPath() . ' Error: ' . $e->getMessage());

            throw $e;
        }

        $image->setFile(null);

        if (null !== $image->getReplacedPath()) {
            $this->deleteByPath($image->getReplacedPath(), $filesystemKey);
            $image->resetReplacedPath();
        }
    }

    public function read(Image $image, $filesystemKey)
    {
        $filesystem = $this->filesystemMap->get($filesystemKey);

        return $filesystem->read($image->getPath());
    }

    public function copy(Image $old, Image $new, $filesystemKey)
    {
        $filesystem = $this->filesystemMap->get($filesystemKey);

        try {
            $filesystem->write($new->getPath(), $this->read($old, $filesystemKey), true);
        } catch (\RuntimeException $e) {
            $this->logger->error('ImageManager: Copying the file failed for path ' . $new->getPath() . ' Error: ' . $e->getMessage());

            throw $e;
        }
    }

    public function delete(Image $image, $filesystemKey)
    {
        $this->deleteByPath($image->getPath(), $filesystemKey);

        foreach ($this->sizes as $key => $size) {
            $tmp = preg_split('/\./', $image->getPath());
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

    public function getPreview(Image $image, $size)
    {
        try {
            if ('original' == $size) {
                return $this->getOriginal($image);
            } else {
                return $this->generatePreview($image, $size);
            }
        } catch (InvalidSizeException $e) {
            return $this->getPreview($image, 'icon');
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
     * @param Image $image
     * @param $size
     * @return string
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \Jahller\Bundle\AttachmentBundle\Exception\InvalidSizeException
     */
    public function generatePreview(Image $image, $size)
    {
        if ($this->isSizeValid($size)) {

            $newFilename = sprintf('%s.%s.png', $image->getFileName(), $size);
            $sizeDimensions = $this->sizes[$size];
            $filePath = sprintf('gaufrette://uploads/%s', $image->getPreviewPath());
            $filesystem = $this->filesystemMap->get('uploads');

            /* If file already exists deliver it */
            if (file_exists($newFilename)) {
                return $this->deliverPreview($newFilename);
            }

            /* If preview file doesn't exist generate it */
            try {
                $resizedFile = $this->imageService->resize($filePath, $sizeDimensions);
                $filesystem->write($newFilename, $resizedFile, true);

                return $this->deliverPreview($newFilename);
            } catch (\RuntimeException $e) {
                // @ToDo What can we do if writing the file fails?
                throw $e;
            }

        }

        throw new InvalidSizeException();
    }

    public function getOriginal(Image $image)
    {
        $filePath = sprintf('gaufrette://uploads/%s', $image->getPreviewPath());

        /* If file already exists deliver it */
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
    }

    public function isSizeValid($size)
    {
        if (array_key_exists($size, $this->sizes)) {
            return true;
        }

        return false;
    }

}