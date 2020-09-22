<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Resource;
use App\Http\Resources\Traits\Attributes;
use App\Http\Resources\Traits\Paginate;

class DatasResource extends Resource
{
    use Attributes, Paginate;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->setAttribute('data', parent::toArray($request));

        if ($this->isValidPaginate()) {
            $this->setAttribute('meta', $this->getPaginate($request));
        }

        return $this->getResponse()->setResponseData($this->getAttributes());
    }
}
