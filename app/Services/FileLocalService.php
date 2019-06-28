<?php

namespace App\Services;

use App\Interfaces\FileUploadManager;

class FileLocalService implements FileUploadManager
{

    /**
     * Realiza o upload da imagem
     * 
     * @return Boolean
     */
    public function upload(\Illuminate\Http\UploadedFile $file, string $newName, string $destination) : \Symfony\Component\HttpFoundation\File\File
    {
        $destination = '/../../public/images/' . $destination;
        return $file->move(__DIR__ . $destination, $newName);
    }

    /**
     * Remove a imagem
     * 
     * @return Boolean
     */
    public function remove(string $file) : bool
    {
        return unlink(__DIR__ . '/../../public/images/' . $file);
    }

}