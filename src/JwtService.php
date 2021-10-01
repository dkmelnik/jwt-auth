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
        'A' => 27,
        'B' => 28,
        'C' => 29,
        'D' => 30,
        'E' => 31,
        'F' => 32,
        'G' => 33,
        'H' => 34,
        'I' => 35,
        'J' => 36,
        'K' => 37,
        'L' => 38,
        'M' => 39,
        'N' => 40,
        'O' => 41,
        'P' => 42,
        'Q' => 43,
        'R' => 44,
        'S' => 45,
        'T' => 46,
        'U' => 47,
        'V' => 48,
        'W' => 49,
        'X' => 50,
        'Y' => 51,
        'Z' => 52,
        '_' => 53,
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

        if (!$token->relationModel instanceof \Illuminate\Contracts\Auth\Authenticatable) {
            return false;
        }
        \Auth::login($token->relationModel);
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

        $model_name = str_replace("_", "\\", $model_name);

        return [$model_name, $model_id];
    }

    protected function cryptModelName(string $modelName): string
    {
        $modelName = str_replace('\\', '_', $modelName);

        $str = str_split($modelName);

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
