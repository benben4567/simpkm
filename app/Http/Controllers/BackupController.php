<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    public function index()
    {
        $directory = storage_path('app') . '/backup-temp';

        $backupFiles = \File::allFiles($directory);

        // Sort files by modified time DESC
        usort($backupFiles, function ($a, $b) {
            return -1 * strcmp($a->getMTime(), $b->getMTime());
        });
        
        return view('pages.admin.backup-db', compact('backupFiles'));
    }

    public function download($fileName)
    {
        $directory = storage_path('app') . '/backup-temp/';

        if (file_exists($directory . $fileName)) {
            $path = $directory . $fileName;
            $downloadFileName = 'BACKUP_SIMPKM_' . $fileName;

            return response()->download($path, $downloadFileName);
        }

        return abort(404);
    }

    public function backup()
    {
        try {
            Artisan::call('backup:run --only-db');
            return redirect()->back()->with('success', 'Sukses! Backup database selesai.');
        } catch (\Exception $e) {
            Log::error("BackupController@backup: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error! Backup database gagal.');
        }
    }
}
