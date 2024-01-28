<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class GetStudentService
{
    public static function checkNim($angkatan, $nim)
    {
        
        $url = config('services.neosiakad.url') . '/api/mahasiswa/get-nim/' . $nim;
        
        $response = Http::withToken(config('services.neosiakad.token'))->get($url);
        
        if ($response->status() != 200) {
            return false;
        }
        
        return $response->json();
    }
}