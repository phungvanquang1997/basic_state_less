<?php

namespace App\JWT;

use App\JWT\Model\RefreshToken;
use App\UserInfo;
use \Firebase\JWT\JWT;

class JsonWebToken implements Tokens
{
    private $refreshTokenTable;

    private $expired_time = 15; // seconds

    public function __construct()
    {
        $this->refreshTokenTable = new RefreshToken();
    }

    public function createAccessToken(UserInfo $user): array
    {
        $payload = [
            'user_id' => $user->id,
            'exp' => time() + $this->expired_time,
        ];
        $token = JWT::encode($payload, env('JWT_SECRET'));
        return ['access_token' => $token];
    }

    public function createRefreshToken(UserInfo $user): array
    {
        $refreshToken = base64_encode($user->id . '_' . now()->toString());
        $this->refreshTokenTable->insert([
            'id' => $user->id,
            'user_id' => $user->id,
            'refresh_token' => $refreshToken,
            'expiration' => date('Y-m-d H:i:s', time() + 3600),
        ]);
        return ['refresh_token' => $refreshToken];
    }

    /**
     * @param $userId
     * @param $clientRefreshToken
     * @return array
     * @throws \Exception
     */
    public function refreshToken($userId, $clientRefreshToken)
    {
        //refresh token
        $refreshToken = $this->refreshTokenTable->getRefreshToken($userId);
        if (null != $refreshToken && $clientRefreshToken === $refreshToken->refresh_token) {
            //check expired time
            if ($refreshToken->expiration >= date('Y-m-d H:i:s', time())) {
                //re-new access_token
                return $this->createAccessToken(new UserInfo($userId));
            }
            throw new \Exception('refresh_token is expired');
        }
        throw new \Exception('refresh_token and user_id are required');
    }

    /**
     * @param $clientToken
     */
    public function verifyToken($clientToken): void
    {
        JWT::decode($clientToken, env('JWT_SECRET'), ['HS256']);
    }
}
