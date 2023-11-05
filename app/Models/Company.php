<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_logo',
        'name',
        'website',
        'location',
        'description',
        'industry',
    ];

    public function job() {
        return $this->hasMany(Job::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function companySizeCategory() {
        return $this->belongsTo(CompanySizeCategory::class, 'company_size_id', 'id');
    }
}
