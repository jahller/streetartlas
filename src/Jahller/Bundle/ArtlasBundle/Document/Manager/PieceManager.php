<?php

namespace Jahller\Bundle\ArtlasBundle\Document\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Jahller\Bundle\ArtlasBundle\Document\Piece;

class PieceManager
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;

    /**
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @param Piece $piece
     * @return $this
     */
    public function persist(Piece $piece)
    {
        $this->documentManager->persist($piece);
        $this->flush($piece);

        return $this;
    }

    /**
     * @param Piece $piece
     * @return $this
     */
    public function update(Piece $piece)
    {
        $this->flush($piece);

        return $this;
    }

    /**
     * @param Piece $piece
     */
    public function delete(Piece $piece)
    {
        $this->documentManager->remove($piece);
        $this->flush();
    }

    /**
     * Wrapper of DocumentManager::flush()
     *
     * @param null $document
     * @return $this
     */
    public function flush($document = null)
    {
        $this->documentManager->flush($document);

        return $this;
    }

}