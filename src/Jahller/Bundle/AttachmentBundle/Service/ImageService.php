<?php

namespace Jahller\Bundle\AttachmentBundle\Service;

use Jahller\Bundle\AttachmentBundle\Document\ExifData;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Monolog\Logger;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Resize file delivered by file system using Imagick
     *
     * @param $imagePath
     * @param $size
     * @return string The function returns the read data or false on failure
     */
    public function resize($imagePath, $size)
    {
        try {
            $tempImage = file_get_contents($imagePath);
            $name = tempnam('/tmp', sha1(uniqid(mt_rand(), true)));
            file_put_contents($name, $tempImage);
            $resizedFile = imagecreatefromjpeg($name);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $sourceImageWidth = imagesx($resizedFile);
        $sourceImageHeight = imagesy($resizedFile);

        if ($sourceImageWidth < $size['x'] && $sourceImageHeight < $size['y']) {
            return file_get_contents($imagePath);
        }

        if ($sourceImageWidth > $size['x'] && $sourceImageHeight > $size['y']) {
            $finalImageHeight = $sourceImageHeight / ($sourceImageWidth / $size['x']);
            $finalImageWidth = $sourceImageWidth / ($sourceImageHeight / $size['y']);
        }

        if ($sourceImageWidth > $size['x']) {
            $finalImageHeight = $sourceImageHeight / ($sourceImageWidth / $size['x']);
            $finalImageWidth = $size['x'];
        }

        if ($sourceImageHeight > $size['y']) {
            $finalImageWidth = $sourceImageWidth / ($sourceImageHeight / $size['y']);
            $finalImageHeight = $size['y'];
        }

        $gdImage = imagecreatetruecolor($finalImageWidth, $finalImageHeight);
        imagecopyresampled(
            $gdImage, $resizedFile,
            0, 0, 0, 0,
            $finalImageWidth, $finalImageHeight,
            $sourceImageWidth, $sourceImageHeight
        );
        imagejpeg($gdImage, $name, 90);

        return file_get_contents($name);
    }

    /**
     * Process Uploaded File and retrieve Latitude and Longitude from image EXIF data
     *
     * @param Image $image
     * @param UploadedFile $file
     * @return Image
     */
    public function processExifData(Image $image, UploadedFile $file)
    {
        $exif = exif_read_data($file, 0, true);
        $gps = $exif['GPS'];

        $exifData = new ExifData();
        $exifData->setLatitude($this->getGps($gps['GPSLatitude'], $gps['GPSLatitudeRef']));
        $exifData->setLongitude($this->getGps($gps['GPSLongitude'], $gps['GPSLongitudeRef']));

        $image->setExifData($exifData);

        return $image;
    }

    /**
     * Converse coordinate and hemisphere short tag to readable latitude/longitude value
     *
     * @param $exifCoord|string
     * @param $hemi|string - hemisphere short tag N,E,S,W
     * @return int
     */
    function getGps($exifCoord, $hemi) {
        $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;
        $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }

    /**
     * Convert coordinate array to float number
     *
     * @param $coordPart
     * @return float|int
     */
    function gps2Num($coordPart) {
        $parts = explode('/', $coordPart);

        if (count($parts) <= 0) {
            return 0;
        }

        if (count($parts) == 1) {
            return $parts[0];
        }

        return floatval($parts[0]) / floatval($parts[1]);
    }
}