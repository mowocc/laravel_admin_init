<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Traits\Response;

class Resource extends JsonResource
{
    use Response;

    /**
     * 保留数组下标
     *
     * @var bool
     */
    public $preserveKeys = true;
}
