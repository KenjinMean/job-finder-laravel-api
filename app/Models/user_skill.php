<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_skill extends Model {
    use HasFactory;

    // rename the table to user_skill because laravel automatically renames the tables to plural
    protected $table = 'user_skill';
}
