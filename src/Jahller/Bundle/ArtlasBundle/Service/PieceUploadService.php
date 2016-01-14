<?php

namespace Jahller\Bundle\ArtlasBundle\Service;

use Jahller\HttpFoundation\File\ApiUploadedFile;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PieceUploadService
 * @package Jahller\Bundle\ArtlasBundle\Service
 * @author @jahller
 */
class PieceUploadService
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;
    /**
     * @var array
     */
    protected $errors;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->errors = array();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function processUpload(Request $request) {
        if (!$request->request->has('imageFile') || null === $request->request->get('imageFile')) {
            array_push($this->errors, 'Please submit an image file');
        }
        if (!$request->request->has('imageName') || null === $request->request->get('imageName')) {
            array_push($this->errors, 'Please submit an image name');
        }
        if (!$request->request->has('imageMimeType') || null === $request->request->get('imageMimeType')) {
            array_push($this->errors, 'Please submit an image mime type');
        }

        $data = $request->request->all();

        if (!$this->hasErrors()) {
            $imageFile = $request->request->get('imageFile');
            $imageName = $request->request->get('imageName');
            $imageMimeType = $request->request->get('imageMimeType');

            $data['imageFile'] = new ApiUploadedFile($imageFile, $imageName, $imageMimeType);

            /**
             * Remove extra fields after processing the uploaded image
             */
            unset($data['imageName']);
            unset($data['imageMimeType']);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasErrors() {
        return count($this->errors) > 0;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
}