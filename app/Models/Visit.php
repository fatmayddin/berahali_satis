<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['date', 'ip', 'path', 'hits'];

    protected $casts = ['date' => 'date'];
}
