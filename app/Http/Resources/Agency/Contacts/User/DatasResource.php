<?php
namespace App\Http\Resources\Agency\Contacts\User;

use App\Http\Resources\Resource;
use App\Http\Resources\Traits\Attributes;
use App\Http\Resources\Traits\Paginate;

class DatasResource extends Resource
{
    use Attributes, Paginate;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->setAttribute('data', $this->makeDatas());

        if ($this->isValidPaginate()) {
            $this->setAttribute('meta', $this->getPaginate($request));
        }

        return $this->getResponse()->setResponseData($this->getAttributes());
    }

    /**
     * 制作列表数据
     *
     * @return array
     */
    private function makeDatas()
    {
        $datas = [];
        foreach ($this->resource as $user) {
            $datas[] = $user->toArray() + [
                'isSuperAdmin' => $user->isSuperAdmin(),
            ];
        }
        return $datas;
    }
}
