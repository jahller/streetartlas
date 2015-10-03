<?php

namespace Jahller\Bundle\ArtlasBundle\Tests\Document;

use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\ArtlasBundle\Document\Tag;
use Jahller\Bundle\ArtlasBundle\Service\ImageManager;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageManagerTest extends WebTestCase
{
    public function testGetPieces()
    {
        $logger = new Logger('jahller');
        $dir = '/../Resources/public/images/pieces/';
        $imageManager = new ImageManager($logger, $dir);

        $pieces = $imageManager->getPieces();
        $this->assertNotEmpty($pieces);
    }

    public function testGetGPS()
    {
        $logger = new Logger('jahller');
        $dir = '/../Resources/public/images/pieces/';
        $imageManager = new ImageManager($logger, $dir);

        $exif = array(
            "GPS" => array(
                "GPSLatitudeRef" => "N",
                "GPSLatitude" => array(
                    0 => "48/1",
                    1 => "7/1",
                    2 => "3477/100"
                ),
                "GPSLongitudeRef" => "E",
                "GPSLongitude" => array(
                    0 => "11/1",
                    1 => "34/1",
                    2 => "716/100"
                ),
                "GPSAltitudeRef" => "\x00",
                "GPSAltitude" => "118742/223",
                "GPSTimeStamp" => array(
                    0 => "16/1",
                    1 => "56/1",
                    2 => "443/100"
                ),
                "GPSImgDirectionRef" => "M",
                "GPSImgDirection" => "90271/992"
            )
        );

        $gps = $exif['GPS'];
        $lat = $imageManager->getGps($gps['GPSLatitude'], $gps['GPSLatitudeRef']);
        $this->assertEquals(48.126325, $lat);

        $lng = $imageManager->getGps($gps['GPSLongitude'], $gps['GPSLongitudeRef']);
        $this->assertEquals(11.568655555556, $lng);
    }
}
