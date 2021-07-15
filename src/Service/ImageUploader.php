<?php

namespace App\Service;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    /**
     * Move to uploaded image
     *
     * @param Form $form Le formulaire duquel extraire le champs image
     * @param string $fieldName Le nom du champs contenant le fichier image
     * @return string Le nouveau nom du fichier
     */
    public function upload(Form $form, string $fieldName, string $fileName = null)
    {
        $imageFile = $form->get($fieldName)->getData();

        if ($imageFile !== null) {
         
            $newFileName = $fileName ?? $this->createFileName($imageFile);
            $imageFile->move($_ENV['UPLOAD_IMAGES_DIRECTORY'], $newFileName);
            
            return $newFileName;
        }
  
        return $fileName;
    }

    public function createFileName(UploadedFile $file)
    {
        return uniqid() . '.' .$file->guessClientExtension();
    }
}