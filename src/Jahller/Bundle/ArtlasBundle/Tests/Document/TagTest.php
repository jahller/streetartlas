<?php

namespace Jahller\Bundle\ArtlasBundle\Tests\Document;

use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\ArtlasBundle\Document\Tag;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagTest extends WebTestCase
{
    public function testTagNew()
    {
        $tag = new Tag();
        $tag->setTitle('Jahller');

        $this->assertEquals(null, $tag->getId());
        $this->assertEquals('Jahller', $tag->getTitle());

        $piece = new Piece();
        $piece->setTitle('Shark');

        $tag->addPiece($piece);
        $this->assertEquals(1, count($tag->getPieces()));

        $tag->removePiece($piece);
        $this->assertEquals(0, count($tag->getPieces()));
    }
}
