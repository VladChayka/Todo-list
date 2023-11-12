<?php

namespace App\Http\Controllers\Auth;

use App\DTO\UserData;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends BaseApiController
{
    public function register(RegisterRequest $request, UserService $userService, AuthService $authService)
    {
        $userData = new UserData(...$request->validated());

        $user =  $userService->store($userData);

        $result = $authService->getTokenAndRefreshToken($user->email, $request->password);

        return $this->successfulResponseWithData($result, Response::HTTP_CREATED);
    }
}
