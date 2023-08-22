<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model {
    use HasFactory;

    protected $table = 'job_types';

    protected $fillable = [
        "job_type",
    ];

    public function jobs() {
        return $this->belongsToMany(Job::class, 'job_job_types', 'job_type_id', 'job_id');
    }
}
