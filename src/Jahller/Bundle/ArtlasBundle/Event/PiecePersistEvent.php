<?php

namespace Jahller\Bundle\ArtlasBundle\Event;

use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Symfony\Component\EventDispatcher\Event;

class PiecePersistEvent extends Event
{
    private $piece;

    public function __construct(Piece $piece)
    {
        $this->piece = $piece;
    }

    public function getPiece()
    {
        return $this->piece;
    }
}