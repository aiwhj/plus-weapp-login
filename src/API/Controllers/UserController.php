<?php
namespace Aiwhj\WeappLogin\API\Controllers;

use Aiwhj\WeappLogin\Models\UserWeapp as UserWeappModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Zhiyi\Plus\Models\User;

class UserController {
	public function SetUserInfo(Request $request, ResponseFactoryContract $response, JWTAuth $auth, UserWeappModel $UserWeapp) {
		$iv = $request->input('iv');
		$encryptedData = $request->input('encryptedData');
		if (!$iv) {
			return $response->json(['message' => '请输入iv'], 422);
		}
		if (!$encryptedData) {
			return $response->json(['message' => '请输入encryptedData'], 422);
		}
		$user = $request->user();
		$usermini = UserWeappModel::where('user_id', $user->id)->first();
		$app = app('wechat.mini_program');
		$userinfo = $app->encryptor->decryptData($usermini->session_key, $iv, $encryptedData);
		$user->name = $userinfo['nickName'];
		$user->sex = $userinfo['gender'];
		$user->headimgurl = $userinfo['avatarUrl'];
		if (!$user->save()) {
			return $response->json(['message' => '设置失败'], 422);
		}
		return $response->json(['Status' => 1, 'message' => '成功'])->setStatusCode(201);
	}
}
