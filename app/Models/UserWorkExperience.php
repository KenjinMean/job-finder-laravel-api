<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWorkExperience extends Model {
    use HasFactory;

    protected $fillable = [
        "user_id",
        "company_name",
        "job_title",
        "position",
        "job_type",
        "work_location_type",
        "location",
        "description",
        "is_current",
        "start_date",
        "end_date",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function skills() {
        return $this->belongsToMany(Skill::class);
    }
}
