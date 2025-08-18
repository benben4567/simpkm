<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CloudStorage
{

    protected static $path = 'simpkm';

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
    // Pastikan fileName memiliki extension
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    if (empty($extension)) {
        $fileName .= '.pdf'; // Default to PDF jika tidak ada extension
    }

    $path = self::$path . '/' . trim($pathName, '/') . '/' . ltrim($fileName, '/');

    try {
        $result = Storage::disk('s3')->put($path, $fileContent, [
            'visibility' => 'public',
            'ACL' => 'public-read'
        ]);

        if (!$result) {
            throw new \Exception("Failed to upload file to S3");
        }

        return [
            'status' => true,
            'path' => $path,
            'full_path' => $path,
            'url' => Storage::disk('s3')->url($path),
        ];
    } catch (\Exception $e) {
        Log::error("CloudStorage upload failed: " . $e->getMessage());
        return [
            'status' => false,
            'error' => $e->getMessage()
        ];
    }
}
}
