<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Resource;

class DataResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->getResponse()->setResponseData(parent::toArray($request));
    }
}
