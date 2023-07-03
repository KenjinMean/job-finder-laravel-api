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
        "firstName",
        "lastName",
        "headline",
        "additionalName",
        "pronouns",
        "about",
        "location",
        "user_id",
        "profile_image",
        "cover_image",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
