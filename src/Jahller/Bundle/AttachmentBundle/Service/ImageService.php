<?php

namespace Jahller\Bundle\AttachmentBundle\Service;

use Jahller\Bundle\AttachmentBundle\Document\ExifData;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Monolog\Logger;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\File\File;

class ImageService
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;
    /**
     * @var array
     */
    protected $errors;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->errors = array();
    }

    /**
     * Resize file delivered by file system using Imagick
     *
     * @param $imagePath
     * @param $size
     * @return string
     * @throws \Exception
     */
    public function resize($imagePath, $size)
    {
        $fileContents = '';

        try {
            $tempImage = file_get_contents($imagePath);
            $name = tempnam('/tmp', sha1(uniqid(mt_rand(), true)));
            file_put_contents($name, $tempImage);
            $resizedFile = imagecreatefromjpeg($name);

            $sourceImageWidth = imagesx($resizedFile);
            $sourceImageHeight = imagesy($resizedFile);

            if ($sourceImageWidth < $size['x'] && $sourceImageHeight < $size['y']) {
                return file_get_contents($imagePath);
            }

            $finalImageWidth = '';
            $finalImageHeight = '';

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
            $fileContents = file_get_contents($name);

        } catch (\Exception $e) {
            $this->addError($e->getMessage());
        }

        return $fileContents;
    }

    /**
     * Process File and retrieve Latitude and Longitude from image EXIF data
     *
     * @param Image $image
     * @param File $file
     * @return Image
     */
    public function processExifData(Image $image, File $file)
    {
        try {
            /**
             * Read EXIF data from image file
             */
            $exif = exif_read_data($file, 0, true);
            /**
             * Check if file has GPS data
             */
            $gps = $exif['GPS'];

            $exifData = new ExifData();
            $exifData->setLatitude($this->getGps($gps['GPSLatitude'], $gps['GPSLatitudeRef']));
            $exifData->setLongitude($this->getGps($gps['GPSLongitude'], $gps['GPSLongitudeRef']));
            $image->setExifData($exifData);
        } catch (\Exception $exception) {
            if ('Symfony\Component\Debug\Exception\ContextErrorException' == get_class($exception)) {
                $this->addError('Currently you can only upload images with GPS data. For more info visit our Help page.');
            } else {
                $this->addError($exception->getMessage());
            }
        }

        return $image;
    }

    /**
     * Converse coordinate and hemisphere short tag to readable latitude/longitude value
     *
     * @param $exifCoord|string
     * @param $hemi|string - hemisphere short tag N,E,S,W
     * @return int
     */
    private function getGps($exifCoord, $hemi) {
        $degrees = count($exifCoord) > 0 ? $this->gpsToNumber($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->gpsToNumber($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->gpsToNumber($exifCoord[2]) : 0;
        $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }

    /**
     * Convert coordinate array to float number
     *
     * @param $coordPart
     * @return float|int
     */
    private function gpsToNumber($coordPart) {
        $parts = explode('/', $coordPart);

        if (count($parts) <= 0) {
            return 0;
        }

        if (count($parts) == 1) {
            return $parts[0];
        }

        return floatval($parts[0]) / floatval($parts[1]);
    }

    /**
     * @param $message
     */
    private function addError($message) {
        array_push($this->errors, $message);
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors() {
        return count($this->errors) > 0;
    }
}