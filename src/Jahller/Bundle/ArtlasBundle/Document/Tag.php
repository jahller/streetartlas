<?php

namespace Jahller\Bundle\ArtlasBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Tag
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $title;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Jahller\Bundle\ArtlasBundle\Document\Piece")
     */
    protected $pieces;

    /**
     * General constructor
     */
    public function __construct()
    {
        $this->pieces = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param Piece $piece
     * @return $this
     */
    public function addPiece(Piece $piece)
    {
        if (!$this->hasPiece($piece)) {
            $this->pieces->add($piece);
        }

        return $this;
    }

    /**
     * @param Piece $piece
     * @return $this
     */
    public function removePiece(Piece $piece)
    {
        if ($this->hasPiece($piece)) {
            $this->pieces->removeElement($piece);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPieces()
    {
        return $this->pieces;
    }

    /**
     * @param Piece $piece
     * @return mixed
     */
    public function hasPiece(Piece $piece)
    {
        return $this->pieces->contains($piece);
    }

}