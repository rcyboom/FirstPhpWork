<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => explode(',',$this->role),
            'avatar' => $this->avatar,
            'phone_number' => $this->phone_number,
            'sex' => $this->sex,
            'state' => $this->state,
            'birthday' => $this->birthday,
            'work_time' => $this->work_time,
            'card_type' => $this->card_type,
            'card_number' => $this->card_number,
            'duty' => $this->duty,
            'level' => $this->level,
            'from' => $this->from,
            'fix_salary' => $this->fix_salary,
            'work_salary' => $this->work_salary,
            'extra_salary' => $this->extra_salary,
            'family_address' => $this->family_address,
            'personal_address' => $this->personal_address,
            'remark' => $this->remark
        ];
    }

    public function with($request)
    {
        return [
           'status' => 'success',
           'status_code' => 200,
        ];
    }
}
