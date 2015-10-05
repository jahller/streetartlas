<?php

namespace Jahller\Bundle\AttachmentBundle\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Jahller\Bundle\AttachmentBundle\Document\File;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Jahller\Bundle\AttachmentBundle\Document\Pdf;

class AttachmentService
{
    /**
     * Resize file delivered by file system using Imagick
     *
     * @param $attachmentPath
     * @param $size
     * @return \Imagick
     */
    public function resize($attachmentPath, $size)
    {
        try {
            $tempImage = file_get_contents($attachmentPath);
            $name = tempnam('/tmp', sha1(uniqid(mt_rand(), true)));
            file_put_contents($name, $tempImage);
            $resizedFile = new \Imagick($name);
        } catch (\ImagickException $e) {
            throw new \ImagickException($e->getMessage());
        }

        $imageWidth = $resizedFile->getimagewidth();
        $imageHeight = $resizedFile->getimageheight();

        if ($imageWidth < $size['x'] && $imageHeight < $size['y']) {
            return file_get_contents($attachmentPath);
        }

        $resizedFile->setimageformat('png');
        if ($imageWidth > $size['x'] && $imageHeight > $size['y']) {
            $resizedFile->scaleimage($size['y'], $size['x'], true);
            $resizedFile->writeimage($name);

            return file_get_contents($name);
        }

        if ($imageWidth > $size['x']) {
            $resizedFile->resizeimage($size['x'], ($imageHeight / ($imageWidth / $size['x'])), \Imagick::FILTER_UNDEFINED, 1);
            $resizedFile->writeimage($name);

            return file_get_contents($name);
        }

        if ($imageHeight > $size['y']) {
            $resizedFile->resizeimage(($imageWidth / ($imageHeight / $size['y'])), $size['y'], \Imagick::FILTER_UNDEFINED, 1);
            $resizedFile->writeimage($name);

            return file_get_contents($name);
        }

        return file_get_contents($attachmentPath);
    }

    public function guessClass(UploadedFile $attachment)
    {
        if (
            true == preg_match('/(jpe?g|png|gif|bmp|tiff)/', $attachment->getMimeType())
        ) {
            return new Image();
        }

        if (true == preg_match('/pdf/', $attachment->getMimeType())) {
            return new Pdf();
        }

        return new File();
    }
}