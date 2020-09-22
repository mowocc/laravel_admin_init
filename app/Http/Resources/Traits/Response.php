<?php

namespace App\Http\Resources\Traits;

use App\Core\Response\ResponseSuccess;

trait Response
{

    /**
     * Response 成功实体对象
     *
     * @var \App\Core\Response\ResponseSuccess
     */
    private $response;

    /**
     * Response 成功实体对象
     *
     * @return \App\Core\Response\ResponseSuccess
     */
    protected function getResponse()
    {
        if (!$this->response) {
            $this->response = new ResponseSuccess();
        }
        return $this->response;
    }
}
