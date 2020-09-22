<?php
namespace App\Http\Controllers\Agency\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\Setting\Org\StoreRequest;
use App\Http\Requests\Agency\Setting\Org\UpdateRequest;
use App\Http\Resources\Common\TreeResource;
use App\Http\Resources\Common\DataResource;
use App\Models\Agency\Agency\Org;
use App\Services\Agency\Setting\OrgService;

class OrgController extends Controller
{

    /**
     * 获取数据列表
     */
    public function getList(Request $request)
    {
        // 获取代理平台
        $orgs = Org::selectAll();

        return new DataResource([
            'dataList' => $orgs->keyBy('id')->toArray(),
            'treeList' => TreeResource::make($orgs)->toTree($request),
        ]);
    }

    /**
     * 创建数据
     */
    public function store(StoreRequest $request)
    {
        $org = OrgService::create()->store($request);

        return new DataResource($org);
    }

    /**
     * 更新数据
     *
     * @param int $id
     */
    public function update(UpdateRequest $request)
    {
        $id = $request->input('id');

        $org = OrgService::create()->update($id, $request);

        return new DataResource($org);
    }

    /**
     * 删除数据
     *
     * @param int $id
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);
        $id = $request->input('id');

        $org = OrgService::create()->destroy($id);

        return new DataResource($org);
    }
}
