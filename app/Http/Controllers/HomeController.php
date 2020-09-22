<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Home\UserResource;
use App\Http\Resources\Common\DataResource;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use App\Http\Requests\Admin\UpdateRequest;
use App\Services\Auth\UserService as AuthUserService;
use App\Services\Auth\UserConfigService;

class HomeController extends Controller
{

    /**
     * 获取用户信息
     *
     * @param Request $request
     * @return DataResource
     */
    public function getUser(Request $request)
    {
        return new UserResource([]);
    }

    /**
     * 更新用户数据
     *
     * @param \Illuminate\Http\Request $request
     */
    public function update(UpdateRequest $request)
    {
        $data['email'] = $email = $request->post('email');
        $data['name'] = $request->post('name');

        $authUserService = new AuthUserService();
        // 验证邮箱是否已存在
        $authUserService->verifyUserExistedByEmail($email, $request->user()->id);
        // 更新用户数据
        $request->user()->fill($data)->save();

        return new UserResource([]);
    }

    /**
     * 更新用户密码
     *
     * @param \Illuminate\Http\Request $request
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $passwordCurrent = $request->post('password_current');
        $password = $request->post('password');

        $authUserService = new AuthUserService();
        // 更新用户密码
        $authUserService->updatePassword($request->user(), $password, $passwordCurrent);

        return new DataResource([]);
    }

    /**
     * 设置用户本地化语言
     *
     * @param \Illuminate\Http\Request $request
     */
    public function setLanguage(Request $request)
    {
        $this->validate($request, [
            'lang' => 'required|string|between:2,16'
        ]);
        $lang = $request->post('lang');

        $config = UserConfigService::create()->setLanguage($request->user(), $lang);

        return new DataResource($config->language);
    }
}
