<?php

namespace Jahller\Bundle\AttachmentBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 *
 * Class Image
 * @package Onemedia\AttachmentBundle\Document
 *
 * @MongoDB\EmbeddedDocument
 */
class File extends Attachment
{

    public function processFile()
    {
        parent::processFile();
    }

    public function getPreviewPath()
    {
        if ($this->isMicrosoftExcelFile()) {
            return 'file.xls.png';
        }
        if ($this->isMicrosoftWordFile()) {
            return 'file.doc.png';
        }
        if ($this->isMicrosoftPowerPointFile()) {
            return 'file.ppt.png';
        }
        if ($this->isAdobePhotoshopFile()) {
            return 'file.psd.png';
        }
        if ($this->isZipFile())
        {
            return 'file.zip.png';
        }


        return 'file.png';
    }

    private function isZipFile()
    {
        return (
            true == preg_match('/zip/', $this->getExtension())
            ||
            true == preg_match('/\zip/', $this->getMimeType())
        );
    }

    private function isAdobePhotoshopFile()
    {
        return (
            true == preg_match('/psd/', $this->getExtension())
            ||
            true == preg_match('/psd/', $this->getMimeType())
            ||
            true == preg_match('/photoshop/', $this->getMimeType())
        );
    }

    private function isMicrosoftExcelFile()
    {
        return (
            true == preg_match('/\/xls/', $this->getExtension())
            ||
            true == preg_match('/\/xlsx/', $this->getExtension())
            ||
            true == preg_match('/\/xls/', $this->getMimeType())
            ||
            true == preg_match('/msexcel/', $this->getMimeType())
            ||
            true == preg_match('/ms-excel/', $this->getMimeType())
            ||
            true == preg_match('/vnd\.openxmlformats-officedocument\.spreadsheetml\.sheet/', $this->getMimeType())
        );
    }

    private function isMicrosoftWordFile()
    {
        return (
            true == preg_match('/\/doc/', $this->getExtension())
            ||
            true == preg_match('/\/docx/', $this->getExtension())
            ||
            true == preg_match('/\/doc/', $this->getMimeType())
            ||
            true == preg_match('/msword/', $this->getMimeType())
            ||
            true == preg_match('/ms-word/', $this->getMimeType())
            ||
            true == preg_match('/vnd\.openxmlformats-officedocument\.wordprocessingml\.document/', $this->getMimeType())
        );
    }

    private function isMicrosoftPowerPointFile()
    {
        return (
            true == preg_match('/\/ppt/', $this->getExtension())
            ||
            true == preg_match('/\/pptx/', $this->getExtension())
            ||
            true == preg_match('/\/ppt/', $this->getMimeType())
            ||
            true == preg_match('/mspowerpoint/', $this->getMimeType())
            ||
            true == preg_match('/ms-powerpoint/', $this->getMimeType())
            ||
            true == preg_match('/vnd\.openxmlformats-officedocument\.presentationml\.presentation/', $this->getMimeType())
        );
    }
}