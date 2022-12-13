<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
	/**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'user';
	
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
			'id' => $this->getKey(),
			'name' => $this->name,
			'email' => $this->email,
			'translated_roles' =>  $this->roles->map(function ($role) {
				return __('roles.' . $role->title);
			}),
			'roles' =>  $this->roles->map(function ($role) {
				return $role->title;
			}),
		];
    }
}
