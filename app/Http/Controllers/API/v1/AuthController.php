<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class AuthController extends Controller
{
    private $loginService;
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            $auth = $this->loginService->execute($credentials);
            return response()->json($auth, 200);
        } catch (Exception $exception){
            return response()->json(["error" => true, "message" => $exception->getMessage()], $exception->getCode());
        }
    }

    public function me(): JsonResponse
    {
        try {
            return response()->json(auth()->user(), 200);
        } catch (Exception $exception){
            return response()->json(["error" => true, "message" => $exception->getMessage()], $exception->getCode());
        }
    }

    public function logout(): JsonResponse
    {
        try {
            auth()->logout();
            return response()->json(["success" => true], 200);
        } catch (Exception $exception){
            return response()->json(["error" => true, "message" => $exception->getMessage()], $exception->getCode());
        }
    }
}
