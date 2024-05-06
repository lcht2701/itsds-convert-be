<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\Collections\GenericCollection;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Auth;
use Exception;
use Gate;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = User::query();
        $users = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return $this->sendResponse('Get User List', 200, new GenericCollection($users, UserResource::class));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            Gate::authorize('create', User::class);
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $data['is_active'] = true; // default active status when new account is created
            $result = User::create($data);
            return $this->sendResponse("User Created", 200, new UserResource($result));
        } catch (AuthorizationException $ex) {
            return $this->sendUnauthorized('You do not have permission to do this action');
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->sendResponse("Get User Detail", 200, new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            Gate::authorize('update', $user);
            $data = $request->validated();
            // Check if a new password is provided
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // Remove password from array if not set
            }
            $user->update($data);
            return $this->sendResponse("User Updated", 200, new UserResource($user));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Category is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            Gate::authorize('delete', $user);
            $user->delete();
            return $this->sendResponse("User Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Category is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    public function showProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendUnauthorized('You do not have permission to do this action');
        }
        return $this->sendResponse("Get User Profile", 200, new UserProfileResource($user));
    }

    public function updateProfile(UpdateUserProfileRequest $request, User $user)
    {
        try {
            Gate::authorize('updateProfile', $user);
            $data = $request->validated();
            // Check if a new password is provided
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // Remove password from array if not set
            }
            $user->update($data);
            return $this->sendResponse("User Updated", 200, new UserResource($user));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("Category is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
