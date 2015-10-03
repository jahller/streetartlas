<?php

namespace Jahller\Bundle\ArtlasBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("StreetArtlas")')->count() > 0);
    }

    public function testInfo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/info');

        $this->assertTrue($crawler->filter('html:contains("PHP Version")')->count() > 0);
    }
}
