<?php


namespace App\JWT;

use App\UserInfo;

interface Tokens
{
    public function createAccessToken(UserInfo $user): array;

    public function createRefreshToken(UserInfo $user): array;

    public function verifyToken($string): void;
}
