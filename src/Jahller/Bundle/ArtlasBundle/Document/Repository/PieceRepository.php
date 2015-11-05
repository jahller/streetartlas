<?php

namespace Jahller\Bundle\ArtlasBundle\Document\Repository;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Jahller\Bundle\ArtlasBundle\Document\Piece;
use Symfony\Component\HttpFoundation\File\File;

class PieceRepository extends DocumentRepository
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentRepository
     */
    protected $repository;

    /**
     * @param DocumentManager $documentManager
     */
    function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
        $this->repository = $this->documentManager->getRepository('JahllerArtlasBundle:Piece');
    }

    /**
     * @param object|string $id
     * @return object
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return array
     */
    public function findActive()
    {
        return $this->repository->findBy(array(
            'active' => true
        ));
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * Find all tags in a certain location
     *
     * @param $latitude
     * @param $longitude
     */
    public function findAllInLocation($latitude, $longitude)
    {

    }
}