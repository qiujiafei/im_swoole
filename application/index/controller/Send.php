<?php
namespace app\index\controller;

use app\common\Util;
use app\common\Sms;
use app\common\RedisAction;
class Send
{
    public function index()
    {
		if (isset($_GET['phone_num']) || is_int($_GET['phone_num'])) {
			$phoneNum = intval($_GET['phone_num']);
		} else {
			return Util::show(config('code.error'), 'PhoneNumberError');
		} 
		//生成一个随机数
		$code = rand(1000, 9999);
		$taskData = [
			'method' => 'sendSms',
			'data' 	 => [
				'phone' => $phoneNum,
				'code'  => $code
			]
		];		

		//抛送进task进程
		$_POST['http_server']->task($taskData);




		
//		$result = Sms::send($phoneNum, $code);
//		if (!$result) {
//			return Util::show(config('code.error'), 'Verification Code Failure');
//		}
//		$redis = new \Swoole\Coroutine\Redis();
//		$redis->connect(config('redis.host'), config('redis.port'));
//		$val = $redis->set(RedisAction::smsKey($phoneNum), $code, config('redis.out_time'));
		return Util::show(config('code.success'), 'success');
    }
}
