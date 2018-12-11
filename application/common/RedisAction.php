<?php
namespace app\common;

class RedisAction
{
	//设置key的格式
	public static $pre = 'sms_';
	//
	public static $userpre = 'user_';
	
	/**
	*返回短信验证码的redis key
	**/
	public static function smsKey($phoneNum)
	{
		return self::$pre.$phoneNum;
	}
	
	/**
	*返回用户的redis key
	**/
	public static function userKey($phone)
	{
		return self::$userpre.$phone;
	}
}
