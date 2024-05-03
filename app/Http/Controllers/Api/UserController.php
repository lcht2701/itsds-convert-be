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
use Gate;
use Hash;

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
        if (Gate::denies('role.admin')) {
            return $this->sendUnauthorized('You do not have permission to do this action');
        }

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true; // default active status when new account is created
        $result = User::create($data);
        return $this->sendResponse("User Created", 200, new UserResource($result));
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
        if (Gate::denies('role.admin')) {
            return $this->sendUnauthorized('You do not have permission to do this action');
        }
        if ($user->trashed()) {
            return $this->sendNotFound("User is not found or already deleted");
        }
        $data = $request->validated();
        // Check if a new password is provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Remove password from array if not set
        }
        $user->update($data);
        return $this->sendResponse("User Updated", 200, new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Gate::denies('role.admin')) {
            return $this->sendUnauthorized('You do not have permission to do this action');
        }
        if ($user->trashed()) {
            return $this->sendNotFound("User is not found or already deleted");
        }
        $user->delete();
        return $this->sendResponse("User Deleted", 200);
    }

    public function showProfile()
    {
        $user = \Auth::user();
        if (!$user) {
            return $this->sendUnauthorized('You do not have permission to do this action');
        }
        return $this->sendResponse("Get User Profile", 200, new UserProfileResource($user));
    }

    public function updateProfile(UpdateUserProfileRequest $request, User $user)
    {
        $data = $request->validated();
        // Check if a new password is provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Remove password from array if not set
        }
        $user->update($data);
        return $this->sendResponse("User Updated", 200, new UserResource($user));
    }
}
