<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use Jahller\Bundle\ArtlasBundle\Document\Manager\PieceManager;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function deleteAction(Request $request, $id)
    {
        $piece = $this->get('jahller.artlas.repository.piece')->find($id);
        $this->get('jahller.artlas.manager.piece')->delete($piece);

        $params = $this->getRefererParams($request);

        return $this->redirect($this->generateUrl(
            $params['_route']
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