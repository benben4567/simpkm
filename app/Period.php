<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = ['tahun', 'status'];

    public function proposals()
    {
      return $this->hasMany(Proposal::class);
    }

}
