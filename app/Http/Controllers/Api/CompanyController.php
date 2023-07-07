<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompanyResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller {

    public function index(): AnonymousResourceCollection {
        try {
            return CompanyResource::collection(Company::query()->orderBy('id', 'desc')->paginate());
        } catch (QueryException $e) {
            return response()->json(['message' => 'error creating job', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreCompanyRequest $request) {
        $user = Auth::user();
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'website' => $validated['website'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'industry' => $validated['industry'],
            ]);
            $path = null;
            if ($company) {
                $path = Storage::disk('public')->put('company_logos', $request->file('company_logo'));
                $path = 'storage/' . str_replace('\\', '/', $path);
                $company->update(['company_logo' => $path]);
            }
            DB::commit();
            return response()->json(['message' => 'company created successfully', 'company' => $company]);
        } catch (\Exception $e) {
            DB::rollback();
            $errorMessage = $e instanceof QueryException ? 'error creating company' : $e->getMessage();
            $statusCode = $e instanceof QueryException ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_BAD_REQUEST;
            return response()->json(['message' => $errorMessage, 'error' => $e->getMessage()], $statusCode);
        }
    }

    public function show(string $id) {
        try {
            return response(Company::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving user company', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCompanyRequest $request, $companyId) {
        $company = Company::findOrFail($companyId);
        $validated = $request->validated();
        try {
            $this->authorize('update', $company);
            $company->update([
                'name' => $validated['name'],
                'website' => $validated['website'],
                'location' => $validated['location'],
                'description' => $validated['description'],
                'industry' => $validated['industry'],
            ]);
            return response()->json(['message' => 'Company updated successfully']);
        } catch (QueryException $e) {
            return response()->json(['message' => 'error updating company', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy(string $id) {
        try {
            $company = Company::findOrFail($id);
            $this->authorize('delete', $company);
            $company->delete();
            return response()->json(['message' => 'company deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error deleting company', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompany() {
        $user = Auth::user();
        try {
            return response()->json($user->companies);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error retrieving user companies', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateCompanyImage(Request $request, $companyId) {
        $company = Company::findOrFail($companyId);
        try {
            $path = Storage::disk('public')->put('company_logos', $request->file('company_logo'));
            $path = 'storage/' . str_replace('\\', '/', $path);
            $company->update(['company_logo' => $path]);

            if ($company) {
                Storage::disk('public')->delete($company->company_logo);
            }
        } catch (QueryException $qe) {
            return response()->json(["message" => "Error Updating Company", "error" => $qe->getMessage()]);
        } catch (FilesystemException $fe) {
            return response()->json(["message" => "Error Storing Image", "error" => $fe->getMessage()]);
        } catch (\Exception $e) {
            return response()->json(["message" => "An unexpected error occurred", "error" => $e->getMessage()]);
        }
    }
}
