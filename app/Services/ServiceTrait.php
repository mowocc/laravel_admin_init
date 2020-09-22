<?php
namespace App\Services;

trait ServiceTrait
{

    /**
     * Create a new service instance.
     *
     * @param mixed ...$parameters            
     * @return static
     */
    public static function create(...$parameters)
    {
        return new static(...$parameters);
    }

    /**
     * Handle dynamic static method calls into the service.
     *
     * @param string $method            
     * @param array $parameters            
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static())->$method(...$parameters);
    }

    /**
     * Handle dynamic method calls into the service.
     *
     * @param string $method            
     * @param array $parameters            
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->$method(...$parameters);
    }
}