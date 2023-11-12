<?php

namespace App\Http\Controllers\Auth;

use App\DTO\UserData;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\StoreTokenRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client as OClient;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends BaseApiController
{
    public function storeToken(StoreTokenRequest $request, UserService $userService, AuthService $authService): JsonResponse
    {
        $userData = new UserData(...$request->validated());

        $user = $userService->getUserByLogin($userData);

        if (!$user) {
            return $this->errorResponse('The login or password is incorrect', Response::HTTP_FORBIDDEN);
        }

        $result = $authService->getTokenAndRefreshToken($user->email, $request->password);

        return $this->successfulResponseWithData($result, Response::HTTP_CREATED);
    }

    public function refreshToken(RefreshTokenRequest $request, AuthService $authService): JsonResponse
    {
        $oClient = OClient::where('password_client', 1)->first();
        $response = Http::asForm()->post(config('app.local_app_url') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refreshToken,
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'scope' => '',
        ]);

        $result = json_decode((string)$response->getBody(), true);
        $result['refreshTokenExpiresAt'] = $authService->getRefreshTokenExpiresAt();

        return $this->successfulResponseWithData($result, Response::HTTP_CREATED);
    }

    public function revokeToken(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $accessToken = $user->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return $this->successfulResponse();
    }
}
