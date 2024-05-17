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
use App\Repositories\User\IUserRepository;
use Auth;
use Exception;
use Gate;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a owner of the ticket solution.
     */
    public function getOwnerList()
    {
        try {
            $owners = $this->userRepository->getOwnerList();
            return $this->sendResponse('Get Owner List', 200, UserResource::collection($owners));
        } catch (Exception $e) {
            return $this->sendInternalError("Error", $e);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = $this->userRepository->paginate();
            return $this->sendResponse('Get User List', 200, new GenericCollection($users, UserResource::class));
        } catch (Exception $e) {
            return $this->sendInternalError("Error", $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            Gate::authorize('create', User::class);
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']); //Hash password before submit to database
            $data['is_active'] = true; // default active status when new account is created
            $result = $this->userRepository->create($data);
            return $this->sendResponse("User Created", 200, new UserResource($result));
        } catch (AuthorizationException $ex) {
            return $this->sendUnauthorized('You do not have permission to do this action');
        } catch (BadRequestException $e) {
            return $this->sendBadRequest("Bad Request", $e);
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $result = $this->userRepository->find($user->id);
            return $this->sendResponse("Get User Detail", 200, new UserResource($result));
        } catch (ModelNotFoundException) {
            return $this->sendNotFound("User is not exist");
        } catch (BadRequestException $e) {
            return $this->sendBadRequest("Bad Request", $e);
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
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
            $result = $this->userRepository->update($user->id, $data);
            return $this->sendResponse("User Updated", 200, new UserResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("User is not exist or already deleted");
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
            $this->userRepository->delete($user->id);
            return $this->sendResponse("User Deleted", 200);
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("User is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    public function showProfile()
    {
        try {
            $currentUser = Auth::user();
            $user = $this->userRepository->find($currentUser->id);
            return $this->sendResponse("Get User Profile", 200, new UserProfileResource($user));
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("User is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }

    public function updateProfile(UpdateUserProfileRequest $request)
    {
        try {
            $currentUser = Auth::user();
            Gate::authorize('updateProfile', $currentUser);
            $data = $request->validated();
            $result = $this->userRepository->update($currentUser->id, $data);
            return $this->sendResponse("User Updated", 200, new UserResource($result));
        } catch (AuthorizationException $e) {
            return $this->sendUnauthorized("You do not have permission to do this action");
        } catch (ModelNotFoundException $ex) {
            return $this->sendNotFound("User is not exist or already deleted");
        } catch (Exception $ex) {
            return $this->sendInternalError("Error", $ex);
        }
    }
}
