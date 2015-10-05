<?php

namespace Jahller\Bundle\ArtlasBundle\Event;

final class PieceEvents
{
    const PIECE_PRE_PERSIST = 'jahller.artlas.piece.pre_persist';
    const PIECE_POST_PERSIST = 'jahller.artlas.piece.post_persist';

    const PIECE_PRE_ADD_ATTACHMENT = 'jahller.artlas.piece.pre_add_attachment';
    const PIECE_ADD_ATTACHMENT = 'jahller.artlas.piece.add_attachment';
    const PIECE_POST_ADD_ATTACHMENT = 'jahller.artlas.piece.post_add_attachment';

    const PIECE_PRE_DELETE_ATTACHMENT = 'jahller.artlas.piece.pre_delete_attachment';
    const PIECE_DELETE_ATTACHMENT = 'jahller.artlas.piece.delete_attachment';
    const PIECE_POST_DELETE_ATTACHMENT = 'jahller.artlas.piece.post_delete_attachment';
}