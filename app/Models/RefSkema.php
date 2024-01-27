<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefSkema extends Model
{
  protected $table = 'ref_skema';

  protected $fillable = [
    'nama',
    'kepanjangan',
    'is_aktif',
  ];
  
  protected $casts = [
    'is_aktif' => 'boolean',
  ];
}
