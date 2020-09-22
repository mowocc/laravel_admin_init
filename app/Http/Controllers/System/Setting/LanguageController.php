<?php
namespace App\Http\Controllers\System\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\Setting\Language\ItemRequest;
use App\Http\Resources\Common\DatasResource;
use App\Http\Resources\Common\DataResource;
use App\Models\Admin\Config\Language;
use App\Services\System\Setting\LanguageService;

class LanguageController extends Controller
{

    /**
     * 获取数据列表
     */
    public function getList(Request $request)
    {
        list ($collection, $total) = LanguageService::create()->getList($request);

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

        $language = LanguageService::create()->saveItem($request, $id);

        return new DataResource($language);
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

        $language = Language::findOrFail($id);
        $language->delete();

        return new DataResource($language);
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

        $language = Language::withTrashed()->findOrFail($id);
        $language->restore();

        return new DataResource($language);
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

        $language = Language::withTrashed()->findOrFail($id);
        $language->forceDelete();

        return new DataResource($language);
    }
}
