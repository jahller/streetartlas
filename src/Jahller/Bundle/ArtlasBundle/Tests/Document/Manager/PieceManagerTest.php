<?php

namespace Jahller\Bundle\ArtlasBundle\Tests\Document\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Jahller\ArtlasBundle\Document\Manager\PieceManager;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PieceManagerTest extends WebTestCase
{
    /** @var PieceManager $manager */
    private $manager;
    /** @var DocumentManager $documentManager */
    private $documentManager;

    protected function setUp()
    {
        $this->documentManager = $this->getMockDocumentManager();
        $this->manager = $this->getMockPieceManager(array(
            $this->documentManager,
        ));
    }

    public function testPersist()
    {
        /**
         * @var Piece $piece
         */
        $piece = $this->getMock('\Jahller\ArtlasBundle\Document\Piece');
        $pieceManager = $this->manager->persist($piece);
        $this->assertInstanceOf('Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager', $pieceManager);

        $pieceManager = $this->manager->update($piece);
        $this->assertInstanceOf('Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager', $pieceManager);

        $this->manager->delete($piece);
    }

    private function getMockDocumentManager()
    {
        return $this->getMockBuilder('Doctrine\ODM\MongoDB\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getMockPieceManager(array $args)
    {
        return $this->getMockBuilder('Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager')
            ->setConstructorArgs($args)
            ->getMockForAbstractClass();
    }
}
