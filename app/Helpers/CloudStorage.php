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

    public static function upload($pathName, $file, $fileName, $visibility = 'public')
    {
        $res = Storage::cloud()->putFileAs(self::$path . '/' . $pathName, $file, $fileName . '.' . $file->extension());

        if (!$res) {
            Log::error("CloudStorage@upload: File {$pathName} failed to upload");

            return [
                'status' => false,
                'path' => null,
                'full_path' => null,
                'url' => null
            ];
        } else {

            if ($visibility == 'public') {
                Storage::cloud()->setVisibility($res, $visibility);
            }
            ;

            $url = Storage::cloud()->url($res);

            $explode = explode(self::$path . '/', $res);
            return [
                'status' => true,
                'path' => $explode[1],
                'full_path' => $res,
                'url' => $url
            ];
        }
    }
}
