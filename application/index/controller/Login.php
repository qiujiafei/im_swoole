<?php
namespace app\index\controller;

use app\common\Util;
use app\common\RedisAction;
use app\common\Redis;

class Login
{
	public function index()
	{
		$phoneNum = intval($_GET['phone_num']);
		$code = intval($_GET['code']);
		if (empty($phoneNum) || empty($code)) {
			return Util::show(config('code.error'), 'phone or redis error');
		}

		try {
			$redisCode = intval(Redis::getInstance()->get(RedisAction::smsKey($phoneNum)));
		} catch (\Exception $e) {
			echo $e->getMessage().'==='.$e->getFile().'==='.$e->getLine();
		}
		if ($redisCode == $code) {
			$data = [
				'user' => $phoneNum,
				'srcKey' => md5(RedisAction::userKey($phoneNum)),
				'time' => time(),
				'isLogin' => true
			];
			try {
				Redis::getInstance()->set(RedisAction::userKey($phoneNum), $data);
			} catch (\Exception $e) {
				return Util::show(config('code.error'), 'set UserInfo fail');
			}
			return Util::show(config('code.success'), 'ok', $data);
		} else {	
			return Util::show(config('code.error'), 'login error');
		}
	}
}
