<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'email',
        'phone',
        'description',
        'company_size_id',
    ];

    public function jobs() {
        return $this->hasMany(Job::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function companySizeCategory() {
        return $this->belongsTo(CompanySizeCategory::class, 'company_size_id', 'id');
    }

    protected static function boot() {
        parent::boot();

        static::updated(function ($company) {
            if ($company->isDirty('name')) {
                // Get all related jobs and update their slugs
                $company->jobs()->each(function ($job) use ($company) {
                    $job->generateSlug($company->name);
                    $job->save();
                });
            }
        });
    }
}
