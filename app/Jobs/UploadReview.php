<?php

namespace App\Jobs;

use App\Helpers\CloudStorage;
use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadReview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $tempPath;
    protected $id_proposal;
    protected $deskripsi;
    protected $acc;

    public function __construct($user, $tempPath, $id_proposal, $deskripsi, $acc = 0)
    {
        $this->user = $user;
        $this->tempPath = $tempPath;
        $this->id_proposal = $id_proposal;
        $this->deskripsi = $deskripsi;
        $this->acc = $acc;
    }

    public function handle()
    {
        try {
            // Validasi file temp
            if (!file_exists($this->tempPath)) {
                throw new \Exception("Temp file not found: {$this->tempPath}");
            }

            $proposal = Proposal::find($this->id_proposal);
            if (!$proposal) {
                throw new \Exception("Proposal not found: {$this->id_proposal}");
            }

            // Generate nama file yang aman
            $title = preg_replace('/[^a-z0-9]+/', '-', strtolower(Str::words($proposal->judul, 7, '')));
            $filename = $proposal->skema . '_' . $title . '_' . time() . '.pdf';

            // Upload ke cloud storage
            $dirname = $proposal->period->tahun . '/review';
            $upload = CloudStorage::upload(
                $dirname,
                file_get_contents($this->tempPath),
                $filename
            );

            if (!$upload['status']) {
                throw new \Exception("Failed to upload file to cloud storage");
            }

            // Buat record review
            $review = $proposal->reviews()->create([
                'user_id' => $this->user['id'],
                'type' => $this->user['roles'],
                'description' => $this->deskripsi,
                'file' => $filename,
                'file_path' => $upload['path'],
                'file_url' => $upload['url'],
                'acc' => $this->acc
            ]);

            // Jika review disetujui
            if ($this->acc == 1) {
                $proposal->update([
                    'file_path' => $upload['path'],
                    'file_url' => $upload['url'],
                    'file' => $filename,
                    'status' => 'selesai'
                ]);
            }

            Log::info('Review uploaded successfully', [
                'proposal_id' => $this->id_proposal,
                'review_id' => $review->id,
                'file_url' => $upload['url']
            ]);
        } catch (\Exception $e) {
            Log::error('UploadReview failed: ' . $e->getMessage(), [
                'proposal_id' => $this->id_proposal,
                'user_id' => $this->user['id'] ?? null
            ]);
        } finally {
            // Bersihkan file temp
            if (file_exists($this->tempPath)) {
                @unlink($this->tempPath);
            }
        }
    }
}

