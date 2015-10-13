<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    /**
     * @param $id
     * @param $size
     * @return Response
     */
    public function getImagePreviewAction($id, $size)
    {
        /**
         * @var Piece $piece
         */
        $piece = $this->get('jahller.artlas.repository.piece')->find($id);
        /**
         * @var Image $image
         */
        $image = $piece->getImage();
        $content = $this->get('jahller.attachment.manager.image')->getPreview($image, $size);

        $response = new Response($content, 202, array('Content-type' => 'image/png'));
        $response->setPrivate();

        /* 1 month = 2.628.000 seconds */
        $response->setMaxAge(2628000);

        return $response;
    }
}