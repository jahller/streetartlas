<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use Jahller\ArtlasBundle\Document\Manager\PieceManager;
use Jahller\Bundle\ArtlasBundle\Form\PieceType;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PieceController extends Controller
{
    public function newAction(Request $request)
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

            return $this->redirectToRoute('jahller_artlas_homepage');
        }

        return $this->render('JahllerArtlasBundle:Piece:new.html.twig', array(
            'form' => $form->createView()
        ));
    }
}