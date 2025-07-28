<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Review;
use App\Helpers\CloudStorage;

class UploadProposal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tempPath;
    protected $filename;
    protected $tahun;
    protected $review_id;

    public function __construct($tempPath, $filename, $tahun, $review_id)
    {
        $this->tempPath = $tempPath;
        $this->filename = $filename;
        $this->tahun = $tahun;
        $this->review_id = $review_id;
    }

    public function handle()
    {
        try {
            // Periksa apakah file temp ada
            if (!file_exists($this->tempPath)) {
                throw new \Exception("Temp file not found: {$this->tempPath}");
            }

            // Dapatkan ekstensi file dari nama file
            $extension = pathinfo($this->filename, PATHINFO_EXTENSION);

            // Jika belum ada ekstensi, tambahkan .pdf
            if (empty($extension)) {
                $this->filename .= '.pdf';
            }

            $review = Review::find($this->review_id);
            if (!$review) {
                throw new \Exception('Review not found for ID: ' . $this->review_id);
            }

            $dirname = $this->tahun . '/proposal';

            // Upload ke Cloud Storage
            $upload = CloudStorage::upload(
                $dirname,
                file_get_contents($this->tempPath),
                $this->filename
            );

            if ($upload['status']) {
                $review->update([
                    'file_path' => $upload['path'],
                    'file_url' => $upload['url'],
                ]);
                Log::info('File uploaded successfully: ' . $upload['url']);
            } else {
                throw new \Exception('Failed to upload file for review ID: ' . $this->review_id);
            }
        } catch (\Exception $e) {
            Log::error('UploadProposal Error: ' . $e->getMessage());
        } finally {
            // Pastikan file temp dihapus
            if (file_exists($this->tempPath)) {
                unlink($this->tempPath);
            }
        }
    }
}
