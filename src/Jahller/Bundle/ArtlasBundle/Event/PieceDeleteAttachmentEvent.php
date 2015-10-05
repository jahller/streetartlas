<?php

namespace Jahller\Bundle\ArtlasBundle\Event;

use Jahller\Bundle\AttachmentBundle\Document\Attachment;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Symfony\Component\EventDispatcher\Event;

class PieceDeleteAttachmentEvent extends Event
{
    private $piece;
    private $attachment;

    public function __construct(Piece $piece, Attachment $attachment)
    {
        $this->piece = $piece;
        $this->attachment = $attachment;
    }

    public function getPiece()
    {
        return $this->piece;
    }

    public function getAttachment()
    {
        return $this->attachment;
    }
}