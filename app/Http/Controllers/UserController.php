<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show(): array
    {
        return $this->userResponse(auth()->getToken()->get());
    }

    public function store(StoreRequest $request): array
    {
        $user = $this->user->create($request->validated()['user']);

        auth()->login($user);

        return $this->userResponse(auth()->refresh());
    }

    public function update(UpdateRequest $request): array
    {
        auth()->user()->update($request->validated()['user']);

        return $this->userResponse(auth()->getToken()->get());
    }

    public function login(LoginRequest $request): array
    {
        $credentials = $request->validated()['user'];
        
        // Check if any users exist in the database
        if (User::count() === 0) {
            return response()->json([
                'errors' => [
                    'message' => 'No users found in the database. Please register first.'
                ]
            ], Response::HTTP_NOT_FOUND);
        }

        // Attempt to authenticate the user
        if (!$token = auth()->attempt($credentials)) {
            // Check if user exists but password is wrong
            $user = User::where('email', $credentials['email'])->first();
            
            if ($user) {
                return response()->json([
                    'errors' => [
                        'message' => 'Invalid password. Please try again.'
                    ]
                ], Response::HTTP_UNAUTHORIZED);
            }
            
            // User doesn't exist
            return response()->json([
                'errors' => [
                    'message' => 'No account found with this email address.'
                ]
            ], Response::HTTP_NOT_FOUND);
        }

        // Authentication successful
        return $this->userResponse($token);
    }

    protected function userResponse(string $jwtToken): array
    {
        return ['user' => ['token' => $jwtToken] + auth()->user()->toArray()];
    }
}
