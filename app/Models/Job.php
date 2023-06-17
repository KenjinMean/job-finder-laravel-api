<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model {
    use HasFactory;

    protected $casts = [
        'posted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
