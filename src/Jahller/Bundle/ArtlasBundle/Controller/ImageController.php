<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\AttachmentBundle\Document\Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function getAttachmentPreviewAction($pieceId, $size)
    {
        /**
         * @var Piece $piece
         */
        $piece = $this->get('jahller.artlas.repository.piece')->find($pieceId);
        /**
         * @var Attachment $attachment
         */
        $attachment = $piece->getAttachment();
        $content = $this->get('jahller.attachment.manager')->getPreview($attachment, $size);

        $response = new Response($content, 202, array('Content-type' => 'image/png'));
        $response->setPrivate();

        /* 1 month = 2.628.000 seconds */
        $response->setMaxAge(2628000);

        return $response;
    }
}