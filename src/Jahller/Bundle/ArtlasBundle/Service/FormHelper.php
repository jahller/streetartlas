<?php

namespace Jahller\Bundle\ArtlasBundle\Service;

use Symfony\Component\Form\Form;

class FormHelper
{
    public function formHasErrors(Form $form)
    {
        return $form->getErrors(true)->count() > 0;
    }
}