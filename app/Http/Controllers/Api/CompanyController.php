<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;

class CompanyController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request) {
        $user = $request->user();
        $validated = $request->validated();

        try {
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'website' => $validated['website'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'industry' => $validated['industry'],
            ]);

            return response()->json(['message' => 'Company created successfully', 'company' => $company]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating company', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
