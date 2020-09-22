<?php
namespace App\Http\Resources\Traits;

trait Attributes
{

    /**
     * 数据属性数组
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * 合并数组到原属性数组
     *
     * @param array $attributes            
     * @return $this
     */
    public function fillAttributes(array $attributes)
    {
        $this->attributes += $attributes;
        
        return $this;
    }

    /**
     * 设置所有属性数组
     *
     * @param array $attributes            
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        
        return $this;
    }

    /**
     * 获取所有属性数组
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * 设置单条属性
     *
     * @param string $key            
     * @param mixed $value            
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        
        return $this;
    }

    /**
     * 获取单条属性
     *
     * @param string $key            
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
    }
}
