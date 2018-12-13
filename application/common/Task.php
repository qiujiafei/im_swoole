<?php
namespace app\common;

use app\common\Sms;
use app\common\Redis;
use app\common\RedisAction;

class Task
{
	/**
	*通过task任务发送短信验证码，并用同步redis方法保存到缓存中
	*/
	public function sendSms($data, $serv = '')
	{
		try {
			$response = Sms::send($data['phone'], $data['code']);
		} catch (\Exception $e) {
			return false;
		}
		//insert redis
		if (!$response) {
			return false;
		}
		Redis::getInstance()->set(RedisAction::smsKey($data['phone']), $data['code'], config('redis.out_time'));
		return true;
	}

	/**
	*通过task机制发送赛况实时数据给客户端
	*/
	public function pushLive($data, $serv)
	{
		$clients = Redis::getInstance()->sMembers(config('redis.live_game_key'));

		foreach ($clients as $fd) {
			$serv->push($fd, json_encode($data));
		}
	}
}
