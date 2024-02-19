<?php

namespace App\Jobs;

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
    protected $filename_temp;
    protected $id_folder_review;
    protected $id_proposal;
    protected $deskripsi;
    protected $acc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $filename_temp, $id_folder_review, $id_proposal, $deskripsi, $acc)
    {
        $this->user = $user;
        $this->filename_temp = $filename_temp;
        $this->id_folder_review = $id_folder_review;
        $this->id_proposal = $id_proposal;
        $this->deskripsi = $deskripsi;
        $this->acc = $acc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $proposal = Proposal::find($this->id_proposal);

        // if exist upload and save to table
        $title = preg_replace('/[^a-z0-9]+/', '-', strtolower(Str::words($proposal->judul, 7, '')));
        $filename = $proposal->skema . '_' . $title . '_' . time() . '.pdf';

        // get local file
        $path = public_path('storage/temp_proposal_review/' . $this->filename_temp);
        $fileData = File::get($path);

        Storage::cloud()->put($this->id_folder_review . "/" . $filename, $fileData);

        // get metadata
        $contents = collect(Storage::cloud()->listContents($this->id_folder_review, false));
        $metadata = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!
        $id_file = $metadata['path'];

        // simpan ke table proposal
        $review = $proposal->reviews()->create([
            'user_id' => $this->user['id'],
            'type' => $this->user['roles'],
            'description' => $this->deskripsi,
            'file' => $id_file,
            'acc' => $this->acc
        ]);

        // check if acc, update status
        if ($this->acc == 1) {
            $acc = $proposal->update([
                'file' => $id_file,
                'status' => 'selesai'
            ]);
        }
    }
}
