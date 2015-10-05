<?php

namespace Jahller\Bundle\AttachmentBundle\Service;

use Jahller\Bundle\AttachmentBundle\Document\ExifData;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
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