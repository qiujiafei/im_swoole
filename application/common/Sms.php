<?php
/**
* Created by PhpStorm.
* User: Dell
* Date: 2018/12/11
* Time: 13:49
*/
namespace app\common;
class Sms
{
	public static function send($phoneNum, $code)
	{
		$params = array(
			'key'   => config('sms.key'), //您申请的APPKEY
			'mobile'    => $phoneNum, //接受短信的用户手机号码
			'tpl_id'    => config('sms.tpl_id'), //您申请的短信模板ID，根据实际情况修改
			'tpl_value' => '#code#='.$code //您设置的模板变量，根据实际情况修改
		);
		$paramstring = http_build_query($params);
		$content = self::juheCurl(config('sms.url'), $paramstring);
		return json_decode($content, true);
	}
																										     
	public static function juheCurl($url, $params = false, $ispost = 0)
	{
		$httpInfo = array();
		$ch = curl_init();
												      
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		if ($ispost) {
	    	curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_URL, $url);
		} else {
			if ($params) {
				curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
		    } else {
				curl_setopt($ch, CURLOPT_URL, $url);
		    }
		}
		$response = curl_exec($ch);
		if ($response === false) {
			//echo "cURL Error: " . curl_error($ch);
			return false;
		}
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$httpInfo = array_merge($httpInfo, curl_getinfo($ch));
		curl_close($ch);
		return $response;
	}
}
