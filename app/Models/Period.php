<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = ['tahun', 'status', 'pendaftaran', 'id_folder', 'id_folder_review','kegiatan'];

    public function proposals()
    {
      return $this->hasMany(Proposal::class);
    }

}
