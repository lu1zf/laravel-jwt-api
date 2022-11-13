<?php

namespace App\Http\Middleware\API\v1;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class ProtectedRouteAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $exception){
            $code = $exception->getCode() > 0 ?: 401;
            return response()->json(["status" => $this->getTokenExceptionMessage($exception)], $code);
        }
        return $next($request);
    }

    private function getTokenExceptionMessage($exception) : string
    {
        $exceptionType = get_class($exception);
        $handledExceptions = [
            "PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException" => "Token is invalid",
            "PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException" => "Token Expired",
            "PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException" => "Authorization token not found"
        ];

        if (array_key_exists($exceptionType, $handledExceptions)){
            return $handledExceptions[$exceptionType];
        }
        return "Could not process authorization";
    }
}
