<?php

namespace Jahller\Bundle\ArtlasBundle\Tests\Document;

use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\ArtlasBundle\Document\Tag;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PieceTest extends WebTestCase
{
    public function testPieceNew()
    {
        $piece = new Piece();
        $piece->setTitle('Jahller');

        $this->assertEquals(null, $piece->getId());
        $this->assertEquals('Jahller', $piece->getTitle());

        $tag = new Tag();
        $tag->setTitle('Shark');

        $piece->addTag($tag);
        $this->assertEquals(1, count($piece->getTags()));

        $piece->removeTag($tag);
        $this->assertEquals(0, count($piece->getTags()));
    }
}
