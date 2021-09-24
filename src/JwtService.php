<?php
namespace CyberLama\JwtAuth;

use CyberLama\JwtAuth\Models\Token;

class JwtService{
    public function checkToken(string $token){
        $dcr = Token::decryptToken($token);
        dd($dcr);
    }
}
