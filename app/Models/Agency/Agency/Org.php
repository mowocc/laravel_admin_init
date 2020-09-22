<?php
namespace App\Models\Agency\Agency;

use App\Models\Agency\Model;
use App\Models\Traits\Agency\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Org extends Model
{
    use SoftDeletes, LogsActivity;

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'agency_orgs';

    /**
     * 查询后隐藏的字段
     *
     * @var array
     */
    protected $hidden = [
        'deleted',
        'created',
        'updated'
    ];

    /**
     * 可以批量赋值的字段
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'path',
        'name',
        'sort'
    ];

    /**
     * 查询所有正常数据
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function selectAll()
    {
        return $this->newQuery()
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }

    /**
     * 查询所有正常数据
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function selectAllById($id)
    {
        if (null == ($org = $this->newQuery()->find($id))) {
            return $this->newCollection();
        }

        return $this->newQuery()->where(function ($query) use ($org) {
            $query->where('path', 'like', $org->path . '.%')->orWhere('id', '=', $org->id);
        })
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }

    /**
     * 查询所有子孙节点
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function selectChildById($id)
    {
        if (null == ($org = $this->newQuery()->find($id))) {
            return $this->newCollection();
        }
        
        return $this->newQuery()->where('path', 'like', $org->path . '.%')
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }
    
    /**
     * 查询多条数据【ID】
     *
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function selectByIds($ids)
    {
        return $this->newQuery()->whereIn('id', $ids)
            ->orderBy('parent_id', 'asc')
            ->orderBy('sort', 'asc')
            ->get();
    }
}
