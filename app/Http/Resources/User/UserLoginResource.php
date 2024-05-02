<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['user']->id,
            'name' => $this['user']->name,
            'access_token' => $this['token'],
            'created_at' => $this['user']->created_at,
            'updated_at' => $this['user']->updated_at
        ];
    }

    public function with(Request $request): array
    {
        return [
            'status' => 'success',
            'message' => null
        ];
    }
}
