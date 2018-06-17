<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Task extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $rs= parent::toArray($request);
        $customerName=$this->customer->name;
        $rs['customerName']=$customerName;
        $rs['accountState']= ($this->account_id>0)?'已收款':'未收款';
        return $rs;
    }
}
