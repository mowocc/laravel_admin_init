<?php
namespace App\Core\Response;

use ArrayObject;
use JsonSerializable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response as HttpResponse;

class Response extends ArrayObject implements JsonSerializable, Responsable
{

    public function toArray()
    {
        return $this->getArrayCopy();
    }

    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request            
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return new HttpResponse($this->getResponse());
    }

    /**
     * 获取 Response 对象数组
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->getArrayCopy();
    }
}