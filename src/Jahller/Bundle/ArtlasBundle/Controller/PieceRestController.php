<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\ArtlasBundle\Form\PieceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class PieceRestController
 * @package Jahller\Bundle\PieceBundle\Controller
 */
class PieceRestController extends FOSRestController
{
    /**
     * @Rest\Get("/pieces", name="get_pieces")
     * @return Response
     */
    public function getPiecesAction()
    {
        return $this->handleView(
            $this->view($this->get('jahller.artlas.repository.piece')->findAll(), 200)
        );
    }

    /**
     * @Rest\Delete("/pieces/{id}", name="delete_piece")
     *
     * @param Piece $id
     * @return Response
     * @throws AccessDeniedException
     */
    public function deletePieceAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Put piece: Access denied');
        }

        $this->get('jahller.artlas.manager.piece')->delete(
            $this->get('jahller.artlas.repository.piece')->find($id)
        );

        return new Response('', 204);
    }

    /**
     * @Rest\Put("/pieces/{piece}", name="put_piece")
     * @ParamConverter(
     *  "piece",
     *  class="Jahller\Bundle\ArtlasBundle\Document\Piece",
     *  options={"id"="piece"}
     * )
     *
     * @param Request $request
     * @param Piece $piece
     * @return Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function putPieceAction(Request $request, Piece $piece)
    {
        $this->get('logger')->info('### HAS TAGS ' . count($piece->getTags()));

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Put piece: Access denied');
        }

        $form = $this->createForm(new PieceType(), $piece, array('method' => 'PUT'));

        $this->get('logger')->info('### HAS TAGS 2 ' . count($piece->getTags()));
        $this->get('logger')->info('### REQUEST ' . $request->getContent());

        $form->handleRequest($request);

        $this->get('logger')->info('### HAS TAGS 3 ' . count($piece->getTags()));

        if ($form->isValid()) {
            $this
                ->get('jahller.artlas.manager.piece')
                ->update($piece);

            $this->get('logger')->info('### HAS TAGS 4 ' . count($piece->getTags()));

            return $piece;
        }

        $this->get('logger')->info('### ERRORS ' . $form->getErrors(true));

        return $this->handleView($this->view($form->getErrors(true, true), 500));
    }
}