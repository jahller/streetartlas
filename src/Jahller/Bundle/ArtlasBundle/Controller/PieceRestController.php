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
     *
     * @param Request $request
     * @param $piece
     * @return Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function putPieceAction(Request $request, $piece)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Put piece: Access denied');
        }

        /**
         * @var Piece $piece
         */
        $piece = $this->get('jahller.artlas.repository.piece')->find($piece);

        $form = $this->createForm(new PieceType(), $piece, array('method' => 'PUT'));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this
                ->get('jahller.artlas.manager.piece')
                ->update($piece);

            return $piece;
        }

        return $this->handleView($this->view($form->getErrors(), 400));
    }
}