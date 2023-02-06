<?php

namespace App\Jobs;

use App\Review;
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
        $path = public_path('storage\\temp_proposal\\'.$this->filename_temp);
        $fileData = File::get($path);

        // $upload = Storage::cloud()->putFileAs($this->id_folder_review, $fileData, $this->filename);
        $upload = Storage::cloud()->put($this->id_folder_review."/".$this->filename, $fileData);
        
        // get metadata
        $contents = collect(Storage::cloud()->listContents($this->id_folder_review, false));
        $file = $contents
            ->where('type', '=', 'file')
            ->where('filename', '=', pathinfo($this->filename, PATHINFO_FILENAME))
            ->where('extension', '=', pathinfo($this->filename, PATHINFO_EXTENSION))
            ->first(); // there can be duplicate file names!
            
        if ($file) {
            $id_file = $file['path'];
            // simpan ke table review
            $review = Review::where('id', $this->review_id)->update([
              'file' => $id_file,
            ]); 
        }
    }
        
}
