<?php

namespace CyberLama\JwtAuth\Traits;

use CyberLama\JwtAuth\Models\Token;
use Illuminate\Database\Eloquent\Model;

trait HasToken
{
    public function createToken()
    {
        /** @var Model $this */
        Token::createToken($this);
    }

    public function token(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Token::class, "model_id", "id");
    }

    public function refrashToken()
    {
        Token::refrashToken($this);
    }


    public function getToken()
    {
    }

    public function tokenExpired()
    {
    }
}
