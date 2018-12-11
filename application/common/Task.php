<?php
namespace app\common;

use app\common\Sms;

class Task
{
	public function sendSms(array $data)
	{
		try {
			$response = Sms::sendSms($data['phone'], $data['code']);
		} catch (\Exception $e) {
			return false;
		}
		//set Redis
		return true;
	}
}
