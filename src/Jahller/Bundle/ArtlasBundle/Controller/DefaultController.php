<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\ArtlasBundle\Form\PieceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new PieceType(), new Piece());

        $form->handleRequest($request);

        if ($form->isValid()) {
            /**
             * @var Piece $piece
             */
            $piece = $form->getData();

            /**
             * @var PieceManager $pieceManager
             */
            $pieceManager = $this->get('jahller.artlas.manager.piece');
            $pieceManager->persist($piece);

            $this->addFlash('success', 'Piece was successfully created');
        }

        return $this->render('JahllerArtlasBundle:Default:index.html.twig', array(
            'pieces' => $this->get('jahller.artlas.image.manager')->getPieces(),
            'form' => $form->createView()
        ));
    }

    public function infoAction()
    {
        return $this->render('JahllerArtlasBundle:Default:info.html.twig', array(
            'info' => phpinfo()
        ));
    }
}