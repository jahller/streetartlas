<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PieceController extends Controller
{
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        return $this->render('JahllerArtlasBundle:Piece:show.html.twig', array(
            'piece' => $this->get('jahller.artlas.repository.piece')->find($id)
        ));
    }
}