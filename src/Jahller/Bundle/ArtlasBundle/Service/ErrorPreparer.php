<?php

namespace Jahller\Bundle\ArtlasBundle\Service;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\IdentityTranslator;

class ErrorPreparer
{
    protected $translator;

    public function __construct(IdentityTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function getFormErrorsArray(Form $form, $prefix = '')
    {
        $errors = array();
        $errors['count'] = count($form->getErrors());
        $errors['count_deep'] = count($form->getErrors(true));
        if (count($form->getErrors()) > 0) {
            $errors['global'] = array();
        }

        /**
         * @var FormError $globalError
         */
        foreach ($form->getErrors() as $globalError) {
            $errors['global'][] = $this->translator->trans($globalError->getMessage());
        }

        /**
         * @var FormInterface $child
         */
        foreach ($form->all() as $name => $child) {
            if (count($child->getErrors()) > 0) {
                $errors[sprintf('%s%s', $prefix, $name)] = array();
            }

            /**
             * @var FormError $error
             */
            foreach ($child->getErrors() as $error) {
                $errors[sprintf('%s%s', $prefix, $name)][] = $this->translator->trans($error->getMessage());
            }

            if (count($child->all()) > 0) {
                foreach($child->all() as $nameChild => $childChild) {
                    $name = sprintf('%s_%s', $name, $nameChild);
                    if (count($childChild->getErrors()) > 0) {
                        $errors[sprintf('%s%s', $prefix, $name)] = array();
                    }
                    foreach ($childChild->getErrors() as $error) {
                        $errors[sprintf('%s%s', $prefix, $name)][] = $this->translator->trans($error->getMessage());
                    }
                }
            }
        }

        return $errors;
    }
}