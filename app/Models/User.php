<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Skill;
use App\Models\Company;
use App\Models\UserEducation;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'github_id',
        'google_id',
        'email_verified_at',
        'refresh_token',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    public function companies() {
        return $this->hasMany(Company::class);
    }

    public function skills() {
        return $this->belongsToMany(Skill::class, 'skill_user')->withTimestamps();
    }

    public function userInfo() {
        return $this->hasOne(UserInfo::class);
    }

    public function userEducations() {
        return $this->hasMany(UserEducation::class);
    }

    public function userWebsites() {
        return $this->hasMany(UserWebsite::class);
    }

    public function userContact() {
        return $this->hasOne(UserContact::class);
    }

    public function userWorkExperiences() {
        return $this->hasMany(UserWorkExperience::class);
    }

    public function savedJobs() {
        return $this->belongsToMany(Job::class, 'user_saved_jobs');
    }
}
