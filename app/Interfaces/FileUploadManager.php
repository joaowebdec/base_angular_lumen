<?php

namespace App\Interfaces;

interface FileUploadManager
{

    public function upload(\Illuminate\Http\UploadedFile $file, string $newName, string $destination);

    public function remove(string $file) : bool;

}