<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JwtHelper;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\UserContactService;
use App\Http\Requests\UpdateUserContactRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserContactController extends Controller {

    private  $userContactService;

    public function __construct(UserContactService $userContactService) {
        $this->userContactService = $userContactService;
    }

    // can create a class that catch all exception that recieves parameter for error to know what error occured
    /** ------------------------------------------------------------------ */
    public function index() {
        try {
            $user = JwtHelper::getUserFromToken();
            $userContact = $this->userContactService->showUserContact($user);

            return response()->json($userContact, Response::HTTP_OK);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Fetch user contact failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** ------------------------------------------------------------------ */
    public function store(UpdateUserContactRequest $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $validatedRequest = $request->validated();
            $this->userContactService->createUserContact($user, $validatedRequest);

            return response()->json(['message' => 'User Contact created successfully'], Response::HTTP_CREATED);
        } catch (ConflictHttpException $e) {
            return ResponseHelper::generateErrorResponse($e, "Create user contact failed.", Response::HTTP_CONFLICT);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Create user contact failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** ------------------------------------------------------------------ */
    public function update(UpdateUserContactRequest $request) {
        try {
            $user = JwtHelper::getUserFromToken();
            $validatedRequest = $request->validated();
            $this->userContactService->updateUserContact($user, $validatedRequest);

            return response()->json(['message' => 'User Contact updated successfully'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::generateErrorResponse($e, "Update user contact failed.", Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Update user contact failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** ------------------------------------------------------------------ */
    public function destroy() {
        try {
            $user = JwtHelper::getUserFromToken();
            $this->userContactService->deleteUserContact($user);

            return response()->json(['message' => 'User Contact deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::generateErrorResponse($e, "Delete user contact failed.", Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return ResponseHelper::generateErrorResponse($e, "Delete user contact failed.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
