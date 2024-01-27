<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = ['degree', 'name', 'kode_pddikti'];

    public function students()
    {
      return $this->hasMany(Student::class);
    }

    public function teacher()
    {
      return $this->hasMany(Teacher::class);
    }

    public function getFullNameAttribute()
    {
      return "{$this->degree} {$this->name}";
    }
}
