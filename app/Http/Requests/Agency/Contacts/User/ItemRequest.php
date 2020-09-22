<?php
namespace App\Http\Requests\Agency\Contacts\User;

use App\Http\Requests\Request;

class ItemRequest extends Request
{

    /**
     * 应用于请求的验证规则。
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'nullable|integer|min:1',
            'org_id' => 'nullable|required_without:id|integer|min:1',
            'is_super_admin' => 'nullable|integer|in:0,1',
            'name' => 'required|string|between:2,32',
            'email' => 'required|string|email|max:128',
            'password' => 'nullable|required_without:id|string|between:8,16'
        ];
    }

    /**
     * 自定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return trans('validation.requests.agency.contacts.user.store-update.messages');
    }

    /**
     * 自定义字段名称。
     *
     * @return array
     */
    public function attributes()
    {
        return trans('validation.requests.agency.contacts.user.store-update.attributes');
    }
}
