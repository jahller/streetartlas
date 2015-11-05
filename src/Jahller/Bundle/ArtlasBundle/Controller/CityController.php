<?php

namespace Jahller\Bundle\ArtlasBundle\Controller;

use Jahller\Bundle\ArtlasBundle\Document\City;
use Jahller\Bundle\ArtlasBundle\Form\CityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class CityController extends Controller
{
    private function indexAction()
    {

    }

    private function newAction(Request $request)
    {
        $form = $this->createForm(new CityType(), new City());

        $form->handleRequest($request);

        if ($form->isValid()) {

        }

        return $this->render('JahllerArtlasBundle:City:new.html.twig', array(
            'form' => $form->createView(),
            'formHasErrors' => ($form->getErrors(true)->count() > 0),
        ));
    }
}