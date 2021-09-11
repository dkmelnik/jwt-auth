<?php

namespace CyberLama\JwtAuth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

//TODO: Сделать и зарегистрировать middleware на проверку токена и авторизацию, токен
//TODO: должен проверяться через сервис
//$service = app('JwtService')
//$service->decryptToken() //array('id' => 1, 'modelName' => 'User')
//TODO: Доделать логику дешифровки
//TODO: Доделать логику создания токена
//TODO: Доделать логику сброса токена
//TODO: Зарегистрировать собственные роуты пакета (запросить токен, сбросить токен)
//TODO: Сделать регистрацию роутов вариативной, в зависимости от конфига (зарегистрировать конфиг)

class Token extends Model
{

    protected $table = 'tokens';
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
    ];
    protected $fillable = [
        'token'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    static function randomStr(int $length): string
    {
        $array = array_flip(self::CRYPT_ABC);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $array[rand(1, count($array) - 1)];
        }

        return $str;
    }


    static function cryptModelName(string $modelName): string
    {
        $str = str_split(strtolower($modelName));

        $out = '';
        foreach ($str as $item) {
            $out .= self::CRYPT_ABC[$item] . self::randomStr(rand(1,5));
        }

        return strrev($out);
    }

    static function decryptModelName(string $cryptStr):string
    {

    }

    static function create(Model $model)
    {
        $token = new self();

    }
}
