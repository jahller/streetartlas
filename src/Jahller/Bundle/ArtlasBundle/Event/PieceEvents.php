<?php

namespace Jahller\Bundle\ArtlasBundle\Event;

final class PieceEvents
{
    const PIECE_PRE_PERSIST = 'jahller.artlas.piece.pre_persist';
    const PIECE_POST_PERSIST = 'jahller.artlas.piece.post_persist';

    const PIECE_PRE_ADD_IMAGE = 'jahller.artlas.piece.pre_add_image';
    const PIECE_ADD_IMAGE = 'jahller.artlas.piece.add_image';
    const PIECE_POST_ADD_IMAGE = 'jahller.artlas.piece.post_add_image';

    const PIECE_PRE_DELETE_IMAGE = 'jahller.artlas.piece.pre_delete_image';
    const PIECE_DELETE_IMAGE = 'jahller.artlas.piece.delete_image';
    const PIECE_POST_DELETE_IMAGE = 'jahller.artlas.piece.post_delete_image';
}