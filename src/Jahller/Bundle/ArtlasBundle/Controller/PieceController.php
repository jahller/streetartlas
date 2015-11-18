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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PieceController extends Controller
{
    public function showAction($id)
    {
        return $this->render('JahllerArtlasBundle:Piece:show.html.twig', array(
            'piece' => $this->get('jahller.artlas.repository.piece')->find($id)
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $piece = $this->get('jahller.artlas.repository.piece')->find($id);

        if (!$piece) {
            throw $this->createNotFoundException(sprintf('Piece with id "%" not found', $id));
        }

        $form = $this->createForm(new PieceType(), $piece);

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

            $this->addFlash('success', 'Piece was successfully updated');

            $serializedPiece = $this->container->get('serializer')->serialize($piece, 'json');

            return new JsonResponse($serializedPiece);
        }

        return $this->render('JahllerArtlasBundle:Piece:update.html.twig', array(
            'piece' => $piece,
            'form' => $form->createView(),
            'pieceFormHasErrors' => $this->get('jahller.artlas.helper.form')->formHasErrors($form),
        ));
    }

    public function toggleActivateAction(Request $request, $id)
    {
        /**
         * @var Piece $piece
         */
        $piece = $this->get('jahller.artlas.repository.piece')->find($id);
        $piece->toggleActive();

        $this->get('jahller.artlas.manager.piece')->update($piece);

        $params = $this->getRefererParams($request);

        return $this->redirect($this->generateUrl(
            $params['_route']
        ));
    }

    public function cityAction(Request $request, $citySlug)
    {
        
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getRefererParams(Request $request) {
        $referer = $request->headers->get('referer');
        $baseUrl = $request->getBaseUrl();
        $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));

        return $this->get('router')->getMatcher()->match($lastPath);
    }
}