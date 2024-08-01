<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkLocationType;
use Illuminate\Http\Request;

class WorkLocationTypeController extends Controller {
    public function index() {
        $workLocationType = WorkLocationType::all();

        return response()->json($workLocationType);
    }
}
