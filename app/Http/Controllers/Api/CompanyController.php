<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCompanyRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection {
        return CompanyResource::collection(Company::query()->orderBy('id', 'desc')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request) {
        $user = Auth::user();

        $path = Storage::disk('public')->put('company_logos', $request->file('company_logo'));
        $path = 'storage/' . str_replace('\\', '/', $path);

        $validated = $request->validated();

        try {
            $company = Company::create([
                'user_id' => $user->id,
                'company_logo' => $path,
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
        $company = Company::findOrFail($id);
        return response($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
        // if ($oldAvatar !== "avatars/default-avatar.png") {
        //     Storage::disk('public')->delete($oldAvatar);
        //   }

        //   auth()->user()->update(['avatar' => $path]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }

    public function getCompany() {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $company = $user->company;
        return response()->json($company);
    }
}
