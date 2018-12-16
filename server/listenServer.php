<?php
class listenServer
{
	const PORT = 8811;

	public function port()
	{	
		//监测端口是否开启
		$shell = "netstat -anp 2>/dev/null | grep ". self::PORT . " |grep LISTEN | wc -l";
		$result = shell_exec($shell);
		if ($result != 1) {
			echo 'error';
		}
	}
}

//用swoole内置毫秒定时器触发
swoole_timer_tick(2000, function($timer_id) {
	(new listenServer)->port();
});
