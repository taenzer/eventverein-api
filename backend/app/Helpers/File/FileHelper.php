<?php

namespace App\Helpers\File;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Intervention\Image\Laravel\Facades\Image;

class FileHelper
{
    public static function fromBase64(string $base64File): UploadedFile
    {
        // Get file data base64 string
        $image = Image::read($base64File);
        $image->scaleDown(width: 300);
        $fileData = base64_decode(Arr::last(explode(',', $image->toPng()->toDataUri())));

        // Create temp file and get its absolute path
        $tempFile = tmpfile();
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        // Save file data in file
        file_put_contents($tempFilePath, $fileData);

        $tempFileObject = new File($tempFilePath);
        $file = new UploadedFile(
            $tempFileObject->getPathname(),
            $tempFileObject->getFilename(),
            $tempFileObject->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        // Close this file after response is sent.
        // Closing the file will cause to remove it from temp director!
        app()->terminating(function () use ($tempFile) {
            fclose($tempFile);
        });

        // return UploadedFile object
        return $file;
    }

    public static function scaleDownImage($path){
        
        
    }
}