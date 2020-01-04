<?php
namespace App\Http\Controllers\Admin\Profile;

use App\Models\RBAC\Profile;

use Illuminate\Http\Request;

use Hash;

class IndexController extends Controller {
    private $configures = [
        'categories' => '分类',
        'tags' => '标签',
        'posts' => '文章',
        'pages' => '页面',
        'accounts' => '账号',
        'links' => '链接',
        'pictures' => '图片',
    ];

	public function profile() { // 编辑资料 ok
		$item = auth('admin')->user();

		return view('admin.profile.index', compact('item'));
	}
	public function updateProfile(Request $request) { // 更新资料 ok
		$user = auth('admin')->user();

        $user->name = $request->name;

        $result = $user->save();

        $profile = $user->profile;

        $profile->qq = $request->qq;
        $profile->wechat = $request->wechat;
        $profile->weibo = $request->weibo;
        $profile->github = $request->github;
        $profile->gitee = $request->gitee;

        $profile->description = $request->description;

        $result_profile = $user->profile()->save($profile);

        if ( $result && $result_profile ) {
            flash('修改资料成功!', 'success');
        } else {
            flash('修改资料失败!', 'error');
        }

        return back();
	}
	public function configures() { // 个人配置
		$item = auth('admin')->user();
        $configures = $this->configures;

		return view('admin.profile.configures', compact('item', 'configures'));
	}
	public function updateConfigures(Request $request) { // 更新配置
		$user = auth('admin')->user();

        foreach ( $this->configures as $key => $value ) {
            $user->profile->{$key} = json_encode($request->{$key});
        }

        $result = $user->profile->save();

        if ( $result ) {
            flash('修改资料成功!', 'success');
        } else {
            flash('修改资料失败!', 'error');
        }

        return back();
	}
	public function avatar() { // 修改头像
		return view('admin.profile.avatar');
	}
	public function updateAvatar() { // 更新头像
		$user_id = auth('admin')->user()->id;
        $user = \App\Models\RBAC\AdminUser::find($user_id);
        
        $user->avatar = $request->avatar;

        $result = $user->save();

        if ( $result ) {
            flash('修改资料成功!', 'success');
        } else {
            flash('修改资料失败!', 'error');
        }

        return back();
	}
	public function password() { // 修改密码 ok
		return view('admin.profile.password');
	}
	public function updatePassword(Request $request) { // 更新密码 ok
		$this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:6|max:20|confirmed',
        ], [
            'old_password.required' => '旧密码不能为空',
            'password.required' => '新密码不能为空',
            'password.min' => '新密码必须大于 6 位',
            'password.min' => '新密码必须小于等于 20 位',
            'password.confirmed' => '两次密码不一致',
        ]);

        if ( !Hash::check($request->input('old_password'), auth('admin')->user()->password) ) {
            return back()->withErrors(['old_password' => '旧密码不正确']);
        }

        $user = auth('admin')->user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('status', '密码修改成功');
	}
}
