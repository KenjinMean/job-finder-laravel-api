<?php

namespace App\Models;

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
        'posted_at',
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
}
