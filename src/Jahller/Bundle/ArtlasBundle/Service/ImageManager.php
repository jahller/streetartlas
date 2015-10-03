<?php

namespace Jahller\Bundle\ArtlasBundle\Service;

use Monolog\Logger;
use Exception;

class ImageManager
{
    protected $logger;
    protected $piecesPath;

    public function __construct(Logger $logger, $path)
    {
        $this->logger = $logger;
        $this->piecesPath = $path;
    }

    /**
     * Get array of pieces from directory
     *
     * @return array
     */
    public function getPieces()
    {
        $exifData = array();
        $dir = __DIR__ . $this->piecesPath;
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    try {
                        if ('..' !== $file && '.' !== $file) {
                            $exif = exif_read_data($dir.'/'.$file, 0, true);
                            $gps = $exif['GPS'];
                            $file = $exif['FILE'];
                            array_push($exifData, array(
                                'fileName' => $file['FileName'],
                                'lat' => $this->getGps($gps['GPSLatitude'], $gps['GPSLatitudeRef']),
                                'lng' => $this->getGps($gps['GPSLongitude'], $gps['GPSLongitudeRef']),
                                'data' => $exif
                            ));
                        }
                    } catch(Exception $e) {
                        $this->logger->error('ImageManager Exception ' . $e->getMessage());
                    }
                }
                closedir($dh);
            }
        }

        return $exifData;
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