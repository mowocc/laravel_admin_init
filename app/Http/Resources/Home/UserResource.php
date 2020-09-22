<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Resource;
use App\Http\Resources\Traits\Attributes;

class UserResource extends Resource
{
    use Attributes;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->fillAttributes($request->user()->only('id', 'name', 'email'));

        $this->setAttribute('isSuperAdmin', $request->user()->isSuperAdmin());

        return $this->getResponse()->setResponseData($this->getAttributes());
    }
}
