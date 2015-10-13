<?php

namespace Jahller\Bundle\ArtlasBundle\Event;

use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Symfony\Component\EventDispatcher\Event;

class PieceAddImageEvent extends Event
{
    private $piece;
    private $image;

    public function __construct(Piece $piece, Image $image)
    {
        $this->piece = $piece;
        $this->image = $image;
    }

    public function getPiece()
    {
        return $this->piece;
    }

    public function getImage()
    {
        return $this->image;
    }
}