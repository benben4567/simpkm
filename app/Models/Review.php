<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'type', 'description', 'file', 'acc'];

    public function proposal()
    {
      return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
