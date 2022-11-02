<?php

namespace App\Http\Resources;
use App\Http\Resources\HobbiesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      return [
        'nama'=>$this->nama,
        'email'=>$this->email,
        'phone'=>$this->phone,
        'hobbies'=>HobbiesResource::collection($this->hobbies),
      ];
    }
}
