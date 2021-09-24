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
        $model = new App\Models . "\\" . ucfirst($decryptTokenArray["model"])();
        $id = ucfirst($decryptTokenArray["id"]);

        dd($model);



        if (!("App\Models2" . $model instanceof Model)) {
            return throw new EnsureTokenIsValidException("Пользователь не авторизован", "401");
        }
        dd("es", $id);
    }
}
