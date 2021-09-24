<?php

namespace CyberLama\JwtAuth;

use App\Exceptions\EnsureTokenIsValidException;
use CyberLama\JwtAuth\Models\Token;
use Illuminate\Database\Eloquent\Model;

class JwtService
{
    public function checkToken(string $token)
    {
        $decryptTokenArray = Token::decryptToken($token);
        $model = "App\Models\\" . ucfirst($decryptTokenArray["model"]);
        $id = $decryptTokenArray["id"];
        //Todo как обработать ?
        try {
            new $model;
        }catch (\Exception $exception){
            return throw new EnsureTokenIsValidException("Пользователь не авторизован", "401");
        }

        if (!new $model instanceof Model) {
            return throw new EnsureTokenIsValidException("Пользователь не авторизован", "401");
        }
        dd("es", $id);
    }
}
