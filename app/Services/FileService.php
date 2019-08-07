<?php

namespace App\Services;

use App\Interfaces\FileUploadManager;

class FileService
{

    /**
     * Gera um novo nome para o arquivo
     * 
     * @return String
     */
    public static function generateName(\Illuminate\Http\UploadedFile $file) : string
    {
        $extension = $file->getClientOriginalExtension();
        return date('YmdHis') . '.' . $extension;
    }


    /**
     * Realiza o upload da imagem
     * 
     * @return String
     */
    public static function upload(FileUploadManager $fileUpload, \Illuminate\Http\UploadedFile $file, string $destination) : string
    {
        $newName = self::generateName($file);
        $res     = $fileUpload->upload($file, $newName, $destination);
        return $res->getFileName();
    }

    /**
     * Remove a imagem
     * 
     * @return Boolean
     */
    public static function remove(FileUploadManager $fileUpload, string $image) : bool
    {
        return $fileUpload->remove($image);
    }

}