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

    /**
     * Create a new job instance.
     */
    public function __construct($user, $tempPath, $id_proposal, $deskripsi, $acc = 0)
    {
        $this->user = $user;
        $this->tempPath = $tempPath;
        $this->id_proposal = $id_proposal;
        $this->deskripsi = $deskripsi;
        $this->acc = $acc;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $proposal = Proposal::find($this->id_proposal);
        if (!$proposal || !file_exists($this->tempPath)) {
            Log::error('Proposal not found or file missing.', [
                'proposal_id' => $this->id_proposal,
                'file' => $this->tempPath
            ]);
            return;
        }

        $title = preg_replace('/[^a-z0-9]+/', '-', strtolower(Str::words($proposal->judul, 7, '')));
        $filename = $proposal->skema . '_' . $title . '_' . time() . '.pdf';

        $fileData = File::get($this->tempPath);
        $dirname = $proposal->period->tahun . '/review';

        $upload = CloudStorage::upload($dirname, $fileData, $filename);

        $proposal->reviews()->create([
            'file' => $filename,
            'user_id' => $this->user['id'],
            'type' => $this->user['roles'],
            'description' => $this->deskripsi,
            'file_path' => $upload['path'],
            'file_url' => $upload['url'],
            'acc' => $this->acc
        ]);

        if ($this->acc == 1) {
            $success = $proposal->update([
                'file_path' => $upload['path'],
                'file_url' => $upload['url'],
                'file' => $filename,
                'status' => 'selesai'
            ]);

            if (!$success) {
                Log::error('Failed to update proposal after approval.', [
                    'proposal_id' => $this->id_proposal,
                    'user_id' => $this->user['id']
                ]);
            }
        }

        // Delete temp file
        File::delete($this->tempPath);
    }
}
