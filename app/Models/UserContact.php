<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContact extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'phone',
        'city',
        'province',
        'country',
        'zip_code',
        "birth_date",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
