<?php


namespace App\JWT\Model;


use Illuminate\Database\Eloquent\Model;

class RefreshToken extends Model
{
    protected $table = 'user_refresh_tokens';

    public $refreshToken;

    public $exp;

    public function getRefreshToken($userId)
    {
        return self::where('user_id', $userId)->first();
    }
}
