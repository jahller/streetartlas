<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\ArtlasBundle\Event\PieceAddImageEvent;
use Jahller\Bundle\ArtlasBundle\Event\PieceEvents;
use Jahller\Bundle\ArtlasBundle\Form\PieceType;
use Jahller\Bundle\AttachmentBundle\Document\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
            $uploadedFile = $piece->getImageFile();

            /** @var Image $image */
            $image = new Image();
            $image = $this->get('jahller.attachment.service.image')->processExifData($image, $uploadedFile);

            /**
             * @todo add image processing to AttachmentBundle\ImageService
             */
            $image->processFile($uploadedFile);

            /** @var $eventDispatcher EventDispatcherInterface */
            $eventDispatcher = $this->get('event_dispatcher');

            $addImageEvent = new PieceAddImageEvent($piece, $image);
            $eventDispatcher->dispatch(PieceEvents::PIECE_PRE_ADD_IMAGE, $addImageEvent);
            $eventDispatcher->dispatch(PieceEvents::PIECE_ADD_IMAGE, $addImageEvent);
            $eventDispatcher->dispatch(PieceEvents::PIECE_POST_ADD_IMAGE, $addImageEvent);

            $this->addFlash('success', 'Piece was successfully created');
        }

        return $this->render('JahllerArtlasBundle:Default:index.html.twig', array(
            'pieces' => $this->get('jahller.artlas.repository.piece')->findActive(),
            'form' => $form->createView(),
            'pieceFormHasErrors' => ($form->getErrors(true)->count() > 0)
        ));
    }

    public function infoAction()
    {
        return $this->render('JahllerArtlasBundle:Default:info.html.twig', array(
            'info' => phpinfo()
        ));
    }
}