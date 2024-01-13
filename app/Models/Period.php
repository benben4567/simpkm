<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = ['tahun', 'status', 'id_folder', 'id_folder_review'];

    public function proposals()
    {
      return $this->hasMany(Proposal::class);
    }

}
