<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Repositories\Interfaces\IUserRepository;
use App\Domain\Responder\Interfaces\IApiHttpResponder;
use App\Exceptions\ApiCustomException;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private readonly IUserRepository $userRepository, private readonly IApiHttpResponder $apiResponder,) {
    }

    public function login(AuthRequest $request): JsonResponse
    {
        $user = $this->userRepository->first(['email' => $request->get('email')]);
        if(Hash::check($request->get('password'), $user?->password)){
            return $this->apiResponder->response(data:
                ['access_token' => $user->createToken('auth_token', [])->plainTextToken], message: __('Success login'));
        } else {
            throw new ApiCustomException(message:__('Invalid user'));
        }
    }
}
