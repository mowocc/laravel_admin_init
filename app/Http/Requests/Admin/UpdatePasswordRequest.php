<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class UpdatePasswordRequest extends Request
{

    /**
     * 应用于请求的验证规则。
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password_current' => 'required|string|between:8,16',
            'password' => 'required|string|between:8,16|confirmed',
            'password_confirmation' => 'required|string|between:8,16'
        ];
    }

    /**
     * 自定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * 自定义字段名称。
     *
     * @return array
     */
    public function attributes()
    {
        return trans('validation.requests.admin.update-password.attributes');
    }
}
