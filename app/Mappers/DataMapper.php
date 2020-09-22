<?php
namespace App\Mappers;

use ArrayObject;
use JsonSerializable;
use Illuminate\Http\Request;

abstract class DataMapper extends ArrayObject implements JsonSerializable
{

    /**
     * The resource request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new mapper instance.
     *
     * @param \Illuminate\Http\Request $request            
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new mapper instance.
     *
     * @param mixed ...$parameters            
     * @return static
     */
    public static function make(...$parameters)
    {
        return new static(...$parameters);
    }

    /**
     * Convert the request to JSON.
     *
     * @param int $options            
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Prepare the request for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Transform the request into an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->request->toArray();
    }
}