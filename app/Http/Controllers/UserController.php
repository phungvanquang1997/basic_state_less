<?php


namespace App\Http\Controllers;


use App\JWT\JsonWebToken;
use App\UserInfo;

class UserController extends Controller
{
    public function getTokens(\Illuminate\Http\Request $request)
    {
        $tokenService = new JsonWebToken();
        return json_encode(array_merge(
            $tokenService->createAccessToken(new UserInfo($request->user_id)),
            $tokenService->createRefreshToken(new UserInfo($request->user_id))));
    }

    public function getData()
    {
        return ['a'=>'b'];
    }
}
