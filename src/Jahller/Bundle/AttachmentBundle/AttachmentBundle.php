<?php

namespace Jahller\Bundle\AttachmentBundle;


use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AttachmentBundle extends Bundle
{
    public function boot()
    {
        MimeTypeGuesser::getInstance()->register(new FileinfoMimeTypeGuesser());

        parent::boot();
    }
}
