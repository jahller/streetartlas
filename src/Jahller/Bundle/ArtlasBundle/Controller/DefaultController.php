<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Jahller\Bundle\ArtlasBundle\Event\PieceAddAttachmentEvent;
use Jahller\Bundle\ArtlasBundle\Event\PieceEvents;
use Jahller\Bundle\ArtlasBundle\Form\PieceType;
use Jahller\Bundle\AttachmentBundle\Document\Attachment;
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

            /** @var Attachment $attachment */
            $attachment = $this->get('jahller.attachment.service')->guessClass($uploadedFile);
            $attachment->setFile($uploadedFile);

            $attachment->processFile();
            $this->get('logger')->notice('Path ' . $attachment->getPath() . ' - Name ' . $attachment->getName(), array('jahller'));

            /** @var $eventDispatcher EventDispatcherInterface */
            $eventDispatcher = $this->get('event_dispatcher');

            $addAttachmentEvent = new PieceAddAttachmentEvent($piece, $attachment);
            $eventDispatcher->dispatch(PieceEvents::PIECE_PRE_ADD_ATTACHMENT, $addAttachmentEvent);
            $eventDispatcher->dispatch(PieceEvents::PIECE_ADD_ATTACHMENT, $addAttachmentEvent);
            $eventDispatcher->dispatch(PieceEvents::PIECE_POST_ADD_ATTACHMENT, $addAttachmentEvent);

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