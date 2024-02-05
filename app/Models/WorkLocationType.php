<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLocationType extends Model {
    use HasFactory;

    protected $fillable = [
        "name",
    ];

    public function jobs() {
        return $this->hasMany(Job::class, 'work_location_type_id');
    }
}
