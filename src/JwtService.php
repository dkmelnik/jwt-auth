<?php

namespace CyberLama\JwtAuth;

use App\Exceptions\EnsureTokenIsValidException;
use CyberLama\JwtAuth\Models\Token;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

class JwtService
{
    /**
     * @throws EnsureTokenIsValidException
     */
    public function checkToken(string $token)
    {
        $decryptTokenArray = Token::decryptToken($token);
        $modelName = $decryptTokenArray["model"];
        $modelPath = "App\Models\\" . ucfirst($modelName);
        $id = $decryptTokenArray["id"];
        $initModel = new $modelPath;

        //Todo как обработать инициализацию модели?

        //является ли моделью
        if (!$initModel instanceof Model) {
            return throw new EnsureTokenIsValidException("Пользователь не авторизован", "401");
        }
        // есть ли у данной модели данное id
        if (!$initModel::where('id', '=', $id)->count() > 0) {
            return throw new EnsureTokenIsValidException("Пользователь не авторизован", "401");
        }
        // есть ли запись в таблице Токенс с данной моделью и данным токеном
        /** @var  $checkDatabaseRecord Eloquent*/
        $checkDatabaseRecord = Token::where("model_id", $id)
            ->where("token", $token)
            ->where("model", ucfirst($modelName));

        if(!$checkDatabaseRecord->count() > 0){
            return throw new EnsureTokenIsValidException("Пользователь не авторизован", "401");
        }

        dd($checkDatabaseRecord->get());




        dd("es", $id);
    }
}
