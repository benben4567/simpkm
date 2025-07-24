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
        if (!file_exists($this->tempPath)) {
            Log::error("Temp file not found: {$this->tempPath}");
            return;
        }

        $fileContent = file_get_contents($this->tempPath);

        $review = Review::find($this->review_id);
        if (!$review) {
            Log::error('Review not found for ID: ' . $this->review_id);
            return;
        }

        $dirname = $this->tahun . '/proposal';

        $upload = CloudStorage::upload($dirname, $fileContent, $this->filename);

        if ($upload['status']) {
            $review->update([
                'file_path' => $upload['path'],
                'file_url' => $upload['url'],
            ]);
            Log::info('File uploaded successfully: ' . $upload['url']);
        } else {
            Log::error('Failed to upload file for review ID: ' . $this->review_id);
        }

        // Clean up
        unlink($this->tempPath);
    }
}
