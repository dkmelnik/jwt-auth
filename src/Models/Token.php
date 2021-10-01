<?php

namespace CyberLama\JwtAuth\Models;

use CyberLama\JwtAuth\JwtService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Token extends Model
{

    protected $table = 'tokens';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    static function getTokenInDecrypt(string $cryptToken): ?self
    {
        /** @var JwtService $service */
        $service = app(JwtService::class);

        list($model_name, $model_id) = $service->decrypt($cryptToken);

        return self::whereModelId($model_id)
            ->whereModel($model_name)
            ->whereToken($cryptToken)
            ->where('ttl', '>=', date('Y-m-d H:i:s'))
            ->get()
            ->first()
        ;
    }

    static function dropOldTokens(int $id, string $modelName)
    {
        self::whereModelId($id)
            ->whereModel($modelName)
            ->where('ttl', '<', date('Y-m-d H:i:s'))
            ->delete()
        ;
    }

    static function createToken(Model $model): self
    {
        /** @var JwtService $service */
        $service = app(JwtService::class);
        $modelName = get_class($model);
        $ttl = Carbon::now()->addDays(30)->format("Y-m-d H:i:s");

        self::dropOldTokens($model->id, $modelName);

        $token = new self();
        $token->model_id = $model->id;
        $token->model = $modelName;
        $token->token = $service->generateToken($model->id, $modelName);
        $token->ttl = $ttl;
        $token->save();

        return $token;
    }

    public function relationModel()
    {
        return $this->belongsTo($this->model, 'model_id', 'id');
    }
}
