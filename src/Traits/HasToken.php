<?php

namespace CyberLama\JwtAuth\Traits;

trait HasToken
{
    public function createToken()
    {
        \Token::create([])
    }

    public function refrashToken()
    {
    }

    public function getToken()
    {
    }

    public function tokenExpired()
    {
    }
}
