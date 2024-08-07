<?php

namespace App\Models;

use App\Models\Skill;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model {
    use HasFactory;

    protected $fillable = [
        "title",
        "location",
        "description",
        "qualifications",
        "responsibilities",
        "salary",
        "benefits",
        "experience_level",
        "category",
        "company_id",
        "application_deadline_at",
        "slug",
    ];

    protected $casts = [
        'posted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    // unused
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function skills() {
        return $this->belongsToMany(Skill::class, 'job_skill')->withTimestamps();
    }

    public function jobTypes() {
        return $this->belongsToMany(JobType::class, 'job_job_types', 'job_id', 'job_type_id');
    }

    public function workLocationTypes() {
        return $this->belongsToMany(WorkLocationType::class);
    }

    public function usersWhoSaved() {
        return $this->belongsToMany(User::class, 'user_saved_jobs');
    }

    protected static function boot() {
        parent::boot();

        static::creating(function ($job) {
            $job->generateSlug($job->company->name);
        });

        static::updating(function ($job) {
            $job->generateSlug($job->company->name);
        });
    }

    public function generateSlug($companyName) {
        $baseSlug = Str::slug($this->title . ' -at- ' . $companyName);

        // Check if a job with the same slug already exists
        $count = self::where('slug', 'like', $baseSlug . '%')->count();

        if ($count > 0) {
            // Append a unique suffix to the slug
            $suffix = $count + 1;
            $slug = $baseSlug . '-' . $suffix;
        } else {
            $slug = $baseSlug;
        }

        $this->slug = $slug;
    }

    # UPDATE job slug creation with this function that prepends job Id so that we can extract the id and use for querying
    // public function generateSlug($companyName) {
    //     // Generate the base slug
    //     $baseSlug = Str::slug($this->title . ' -at- ' . $companyName);

    //     // Prepend the job ID to the slug
    //     $this->slug = $this->id . '-' . $baseSlug;
    // }
}
