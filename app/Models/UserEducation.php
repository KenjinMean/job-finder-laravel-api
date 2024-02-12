<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model {
    use HasFactory;

    // declare table name here because for some reason laravel dont automatically map
    // this UserEducation model to user_educations table
    protected $table = 'user_educations';

    protected $fillable = [
        'user_id',
        'institution_name',
        'degree',
        'major',
        'start_date',
        "end_date",
        'graduation_status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
