<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use Uuids;
    
    protected $fillable = ['major_id', 'nim', 'nama', 'tempat_lahir', 'tgl_lahir', 'no_hp', 'jk', 'username_sim', 'password_sim'];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function major()
    {
      return $this->belongsTo(Major::class);
    }

    public function getTanggalAttribute()
    {
      return Carbon::createFromFormat('Y-m-d', $this->tgl_lahir)->format('d/m/Y');
    }

    public function proposals()
    {
      return $this->belongsToMany(Proposal::class)->withPivot('jabatan');
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = ucwords($value);
    }

    public function setTempatLahirAttribute($value)
    {
        $this->attributes['tempat_lahir'] = ucwords($value);
    }
}
