<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CloudStorage
{

    protected static $path = 'data-simpkm';

    public static function dirExist($name)
    {
        $list = Storage::cloud()->allDirectories();

        $res = in_array(self::$path . '/' . $name, $list);

        if ($res) {
            return [
                'status' => true,
                'path' => $name,
                'full_path' => self::$path . '/' . $name
            ];
        } else {
            return [
                'status' => false,
                'path' => null,
                'full_path' => null
            ];
        }
    }

    public static function makeDirectory($dirName)
    {
        $res = Storage::cloud()->makeDirectory(self::$path . '/' . $dirName);

        if (!$res) {
            return [
                'status' => false,
                'path' => null,
                'full_path' => null
            ];
        } else {
            return [
                'status' => true,
                'path' => $dirName,
                'full_path' => self::$path . '/' . $dirName
            ];
        }
    }

    public static function deleteDirectory($dirName)
    {
        $res = Storage::disk('s3')->deleteDirectory(self::$path . '/' . $dirName);

        return $res;
    }

    public static function deleteFile($fileName)
    {
        $res = Storage::cloud()->delete(self::$path . '/' . $fileName);

        return $res;
    }

    public static function upload($pathName, $fileContent, $fileName, $visibility = 'public')
    {
        // Pastikan fileName memiliki ekstensi yang benar
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Jika fileContent adalah file object (UploadedFile)
        if (is_object($fileContent) && method_exists($fileContent, 'extension')) {
            $extension = $fileContent->extension();
            $fileContent = file_get_contents($fileContent->getRealPath());
        }

    // Bangun path lengkap
        $path = self::$path . '/' . $pathName . '/' . $fileName;

        // Upload ke S3
        $result = Storage::disk('s3')->put($path, $fileContent, $visibility);

        if (!$result) {
            Log::error("CloudStorage@upload: File {$path} failed to upload");
            return [
                'status' => false,
                'path' => null,
                'full_path' => null,
                'url' => null
            ];
        }

        $url = Storage::disk('s3')->url($path);

        return [
            'status' => true,
            'path' => $path,
            'full_path' => $path,
            'url' => $url,
        ];
    }
}
