<?php
namespace Aiwhj\WeappLogin\API\Controllers;

use Aiwhj\WeappLogin\Models\UserWeapp as UserWeappModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Http\Request;
use RuntimeException;
use Tymon\JWTAuth\JWTAuth;
use Zhiyi\Plus\Models\CommonConfig;
use Zhiyi\Plus\Models\User;

class LoginController {
	public function loginByCode(Request $request, ResponseFactoryContract $response, JWTAuth $auth, UserWeappModel $UserWeapp) {
		$code = $request->input('code');
		if (!$code) {
			return $response->json(['message' => '请输入code'], 422);
		}
		$app = app('wechat.mini_program');
		$sessionkey = $app->auth->session($code);
		if (!$sessionkey['openid']) {
			return $response->json(['message' => '获取授权失败'], 422);
		}
		$UserMinipro = UserWeappModel::where('openid', $sessionkey['openid'])->first();
		if ($UserMinipro) {
			$UserMinipro->session_key = $sessionkey['session_key'];
			$UserMinipro->save();
			$user = User::find($UserMinipro->user_id);
		} else {
			$user = new User;
			$user->name = '匿名' . str_random(8);
			if (!$user->save()) {
				return $response->json(['message' => ['注册失败']], 500);
			}
			$UserWeapp->user_id = $user->id;
			$UserWeapp->openid = $sessionkey['openid'];
			$UserWeapp->session_key = $sessionkey['session_key'];
			if (isset($sessionkey['unionid'])) {
				$UserWeapp->unionid = $sessionkey['unionid'];
			}
			$role = CommonConfig::byNamespace('user')
				->byName('default_role')
				->firstOr(function () {
					throw new RuntimeException('Failed to get the defined user group.');
				});
			$user->roles()->sync($role->value);
			if (!$UserWeapp->save()) {
				return $response->json(['message' => ['储存授权失败']], 500);
			}
		}
		$token = $this->createToken($user, $auth);
		return $response->json(['token' => $token, 'user' => $user])->setStatusCode(201);
	}
	public function createToken(User $user, JWTAuth $auth) {
		return [
			'token' => $auth->fromUser($user),
			'ttl' => config('jwt.ttl'),
			'refresh_ttl' => config('jwt.refresh_ttl'),
		];
	}
}
