<?php

namespace App\Http\Middleware;

use App\JWT\JsonWebToken;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $tokenService = new JsonWebToken();
        try {
            $tokenService->verifyToken($request->access_token);
            return $next($request);
        } catch (ExpiredException $exception) {
            try {
                return response()->json($tokenService->refreshToken($request->user_id, $request->refresh_token));
            } catch (\Exception $exception){
                return response()->json(['message' => $exception->getMessage()]);
            }
        } catch (SignatureInvalidException $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        } catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()]);
        }
    }
}
