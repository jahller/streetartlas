<?php

namespace Jahller\Bundle\ArtlasBundle\Document\Manager;

use Doctrine\ODM\MongoDB\DocumentManager;
use Jahller\Bundle\ArtlasBundle\Document\City;

class CityManager
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $documentManager;

    /**
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @param City $city
     * @return $this
     */
    public function persist(City $city)
    {
        $this->documentManager->persist($city);
        $this->flush($city);

        return $this;
    }

    /**
     * @param City $city
     * @return $this
     */
    public function update(City $city)
    {
        $this->flush($city);

        return $this;
    }

    /**
     * @param City $city
     */
    public function delete(City $city)
    {
        $this->documentManager->remove($city);
        $this->flush();
    }

    /**
     * Wrapper of DocumentManager::flush()
     *
     * @param null $document
     * @return $this
     */
    public function flush($document = null)
    {
        $this->documentManager->flush($document);

        return $this;
    }

}