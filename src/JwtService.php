<?php

namespace CyberLama\JwtAuth;

use CyberLama\JwtAuth\Models\Token;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class JwtService
{
    protected const SEPARATOR_CHARACTER = "|";
    protected const CRYPT_ABC = [
        'a' => 1,
        'b' => 2,
        'c' => 3,
        'd' => 4,
        'e' => 5,
        'f' => 6,
        'g' => 7,
        'h' => 8,
        'i' => 9,
        'j' => 10,
        'k' => 11,
        'l' => 12,
        'm' => 13,
        'n' => 14,
        'o' => 15,
        'p' => 16,
        'q' => 17,
        'r' => 18,
        's' => 19,
        't' => 20,
        'u' => 21,
        'v' => 22,
        'w' => 23,
        'x' => 24,
        'y' => 25,
        'z' => 26,
        '_' => 27,
    ];

    /**
     * Проверяет токен из запроса на валидность
     * @throws Exception\TokenNotValid
     */
    public function checkToken(string $token): bool
    {
        $token = Token::getTokenInDecrypt($token);

        if (!$token instanceof Token) {
            return false;
        }

        if (!$token->relationModel instanceof Model) {
            return false;
        }

        return $token instanceof Token;
    }

    public function generateToken(int $id, string $modelName): string
    {
        return $id . self::SEPARATOR_CHARACTER . $this->cryptModelName($modelName);
    }

    public function decrypt($token): array
    {
        $cutStr = stristr($token, self::SEPARATOR_CHARACTER);
        //заменяем буквенные символы на *
        $newStr = strrev(preg_replace('/[^0-9]/', '*', $cutStr));
        //добавляем в массив числа разделенные *
        $array = array_diff(explode("*", $newStr), array(''));
        $flipABC = $this->flipCryptABC();

        $model_id = stristr($token, self::SEPARATOR_CHARACTER, true);
        $model_name = '';
        foreach ($array as $item) {
            $model_name .= $flipABC[$item];
        }

        $modelTransform = explode('_', $model_name);
        $modelTransform = array_map(function ($item) {
            return ucfirst($item);
        }, $modelTransform);
        $model_name = implode('\\', $modelTransform);

        return [$model_name, $model_id];
    }

    protected function cryptModelName(string $modelName): string
    {
        $modelName = str_replace('\\', '_', $modelName);

        $str = str_split(strtolower($modelName));

        $out = '';
        foreach ($str as $item) {
            $out .= self::CRYPT_ABC[$item] . $this->randomStr(rand(1, 5));
        }

        return strrev($out);
    }
    protected function randomStr(int $length): string
    {
        $flipABC = $this->flipCryptABC();
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $flipABC[rand(1, count($flipABC) - 1)];
        }

        return $str;
    }
    protected function flipCryptABC(): array
    {
        return array_flip(self::CRYPT_ABC);
    }
}
