<?php
namespace app\common;

class Util
{
    public static function show($status, $message = '', $data = '')
    {
	$result = [
	    'status' => $status,
	    'message' => $message,
	    'data' => $data
	];
	return json_encode($result);
    }
}
