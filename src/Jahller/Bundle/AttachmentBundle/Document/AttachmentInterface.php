<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

/**
 *
 * Interface AttachmentInterface
 * @package Onemedia\AttachmentBundle\Document
 */
interface AttachmentInterface
{
    public function setFile($file);

    public function getFile();
}