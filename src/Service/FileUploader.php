<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

    public function __construct(private string $targetDirectory, private SluggerInterface $slugger)
    {
    }

    public function upload(UploadedFile $file, string $directory = '') : string //On a un tout petit peu modifié la doc : on passe le répertoire en deuxième paramètre
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory() . $directory, $fileName); //On fait une concaténation pour que ça aille dans le bon directory
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }


    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }




}