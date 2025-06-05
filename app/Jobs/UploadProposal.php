<?php

namespace App\Jobs;

use App\Helpers\CloudStorage;
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadProposal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filename_temp;
    protected $filename;
    protected $id_folder_review;
    protected $review_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename_temp, $filename, $id_folder_review, $review_id)
    {
        $this->filename_temp = $filename_temp;
        $this->filename = $filename;
        $this->id_folder_review = $id_folder_review;
        $this->review_id = $review_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get local file
        $path = public_path('storage/temp_proposal/' . $this->filename_temp);
        // $path = public_path('storage\\temp_proposal\\'.$this->filename_temp);
        $fileData = File::get($path);

        $review = Review::find($this->review_id);
        if (!$review) {
            Log::error('Review not found for ID: ' . $this->review_id);
            return;
        }

        $dirname = $review->proposal->period->tahun . '/proposal';

        // $upload = Storage::cloud()->put($this->id_folder_review . "/" . $this->filename, $fileData); //google drive
        $upload = CloudStorage::upload($dirname, $fileData, $this->filename);

        // update DB
        Review::where('id', $this->review_id)->update(['file_path' => $upload['path'], 'file_url' => $upload['url']]);
        Log::info('File uploaded successfully: ' . $upload['url']);

        // delete temp file
        $tempPath = public_path('storage/temp_proposal/' . $this->filename_temp);
        if (File::exists($tempPath)) {
            File::delete($tempPath);
            Log::info('Temporary file deleted: ' . $tempPath);
        } else {
            Log::warning('Temporary file not found: ' . $tempPath);
        }
    }

}
