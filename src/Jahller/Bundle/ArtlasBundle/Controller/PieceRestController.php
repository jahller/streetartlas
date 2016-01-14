<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\ArtlasBundle\Event\PieceAddImageEvent;
use Jahller\Bundle\ArtlasBundle\Event\PieceEvents;
use Jahller\Bundle\ArtlasBundle\Form\PieceType;
use Jahller\Bundle\ArtlasBundle\Service\PieceUploadService;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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

    /**
     * @Rest\Post("/pieces/new", name="post_piece")
     *
     * @param Request $request
     * @return Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function postPieceAction(Request $request)
    {
        /**
         * @var $data PieceUploadService
         */
        $pieceUploadService = $this->get('jahller.artlas.service.piece_upload');
        $data = $pieceUploadService->processUpload($request);
        if ($pieceUploadService->hasErrors()) {
            return $this->handleView($this->view(array('global' => $pieceUploadService->getErrors()), 400));
        }

        $form = $this->createForm(new PieceType(), null, array('method' => 'POST'));
        $form->submit($data);

        if ($form->isValid()) {
            /**
             * @var Piece $piece
             */
            $piece = $form->getData();
            $this->get('logger')->info('' . $piece->getImageFile()->getClientOriginalName(), array('jahller'));

            /**
             * @var PieceManager $pieceManager
             */
            $pieceManager = $this->get('jahller.artlas.manager.piece');
            $pieceManager->persist($piece);

            /** @var \Symfony\Component\HttpFoundation\File\File $imageFile */
            $imageFile = $piece->getImageFile();
            $imageService = $this->get('jahller.attachment.service.image');

            /** @var Image $image */
            $image = $this->get('jahller.attachment.service.image')->processExifData(new Image(), $imageFile);
            if ($imageService->hasErrors()) {
                return $this->handleView($this->view(array('imageFile' => $imageService->getErrors()), 400));
            }

            $image->processFile($imageFile);

            /** @var $eventDispatcher EventDispatcherInterface */
            $eventDispatcher = $this->get('event_dispatcher');

            $addImageEvent = new PieceAddImageEvent($piece, $image);
            $eventDispatcher->dispatch(PieceEvents::PIECE_PRE_ADD_IMAGE, $addImageEvent);
            $eventDispatcher->dispatch(PieceEvents::PIECE_ADD_IMAGE, $addImageEvent);
            $eventDispatcher->dispatch(PieceEvents::PIECE_POST_ADD_IMAGE, $addImageEvent);

            return $piece;
        }

        return $this->handleView($this->view($form->getErrors(), 400));
    }
}