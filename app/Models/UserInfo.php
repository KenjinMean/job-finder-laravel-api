<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "headline",
        "additional_name",
        "about",
        "user_id",
        "profile_image",
        "cover_image",
        'resume',
        "birth_date",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
