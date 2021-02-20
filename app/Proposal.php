<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = ['period_id', 'skema', 'judul', 'status', 'file'];

    public function period()
    {
      return $this->belongsTo(Period::class);
    }

    public function students()
    {
      return $this->belongsToMany(Student::class)->withPivot('jabatan');
    }

    public function teachers()
    {
      return $this->belongsToMany(Teacher::class)->withPivot('jabatan');
    }

    public function pembimbing()
    {
      return $this->belongsToMany(Teacher::class)->wherePivot('jabatan', 'Pembimbing');
    }

    public function ketua()
    {
      return $this->belongsToMany(Student::class)->wherePivot('jabatan', 'Ketua');
    }

    public function anggota()
    {
      return $this->belongsToMany(Student::class)->wherePivot('jabatan', 'Anggota');
    }

    public function reviewer()
    {
      return $this->belongsToMany(Teacher::class)->wherePivot('jabatan', '!=', 'Pembimbing');
    }

    public function reviewer1()
    {
      return $this->belongsToMany(Teacher::class)->wherePivot('jabatan', 'Reviewer 1');
    }

    public function reviewer2()
    {
      return $this->belongsToMany(Teacher::class)->wherePivot('jabatan', 'Reviewer 2');
    }

    public function scopeLolos($query)
    {
      return $query->where('status', '=', 'lolos');
    }
}
