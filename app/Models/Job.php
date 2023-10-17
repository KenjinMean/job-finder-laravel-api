<?php

namespace App\Models;

use App\Models\Skill;
use Illuminate\Support\Str;
use App\Policies\JobSkillPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model {
    use HasFactory;

    protected $fillable = [
        "company_id",
        "category_id",
        "title",
        "location",
        "description",
        "requirements",
        "salary",
        "posted_at",
        "slug",
    ];

    protected $casts = [
        'posted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function skills() {
        return $this->belongsToMany(Skill::class, 'job_skill')->withTimestamps();
    }

    public function jobTypes() {
        return $this->belongsToMany(JobType::class, 'job_job_types', 'job_id', 'job_type_id');
    }

    protected static function boot() {
        parent::boot();

        static::creating(function ($job) {
            $baseSlug = Str::slug($job->title . ' -at- ' . $job->company->name);

            // Check if a job with the same slug already exists
            $count = self::where('slug', $baseSlug)->count();

            if ($count > 0) {
                // Append a unique suffix to the slug
                $uniqueSuffix = time(); // used time as unique identifier
                $slug = $baseSlug . '-' . $uniqueSuffix;
            } else {
                $slug = $baseSlug;
            }

            $job->slug = $slug;
        });
    }
}
