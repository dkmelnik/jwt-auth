<?php

namespace CyberLama\JwtAuth\Resource;

use CyberLama\JwtAuth\Models\Token;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Token $this */
        return [
            "token" => $this->token,
            'ttl' => $this->ttl
        ];
    }
}
