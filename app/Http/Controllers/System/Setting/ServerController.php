<?php
namespace App\Http\Controllers\System\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\Setting\Server\ItemRequest;
use App\Http\Resources\Common\DatasResource;
use App\Http\Resources\Common\DataResource;
use App\Models\Admin\Config\Server;
use App\Services\System\Setting\ServerService;

class ServerController extends Controller
{

    /**
     * 获取数据列表
     */
    public function getList(Request $request)
    {
        list ($collection, $total) = ServerService::create()->getList($request);

        return DatasResource::make($collection)->setPaginateTotal($total);
    }

    /**
     * 保存数据
     *
     * @param int $id
     */
    public function saveItem(ItemRequest $request)
    {
        $id = $request->input('id', null);

        $server = ServerService::create()->saveItem($request, $id);

        return new DataResource($server);
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

        $server = Server::findOrFail($id);
        $server->delete();

        return new DataResource($server);
    }

    /**
     * 恢复删除
     *
     * @param int $id
     */
    public function restore(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);
        $id = $request->input('id');

        $server = Server::withTrashed()->findOrFail($id);
        $server->restore();

        return new DataResource($server);
    }

    /**
     * 彻底删除
     *
     * @param int $id
     */
    public function forceDestroy(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1'
        ]);
        $id = $request->input('id');

        $server = Server::withTrashed()->findOrFail($id);
        $server->forceDelete();

        return new DataResource($server);
    }
}
