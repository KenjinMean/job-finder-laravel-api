<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

# AUTHENTICATION ROUTES
require __DIR__ . "/api/auth_route/authRoutes.php";

# JOB ROUTES
require __DIR__ . "/api/job_route/jobRoutes.php";

# COMPANY ROUTES
require __DIR__ . "/api/company_route/companyRoutes.php";

# SKILL ROUTES
require __DIR__ . "/api/skill_route/skillRoutes.php";

# USER ROUTES
require __DIR__ . "/api/user_route/userRoutes.php";
