<?php
namespace app\common;

class Redis
{
	public $redis = '';

	//定义单例模式的变量
	private static $_instance = null;
	

	//获得一个实例
	public static function getInstance()
	{
		if (empty(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}


	private function __construct()
	{
		$this->redis = new \Redis();
		$result = $this->redis->connect(config('redis.host'), config('redis.port'), config('redis.timeOut'));
		if ($result === false) {
			throw new \Exception('redis connect error');
		}
	}

	public function set($key, $val, $time = 0)
	{
		if (!$key) {
			return '';
		}

		if (is_array($val)) {
			$val = json_encode($val);
		}

		if (!$time) {
			return $this->redis->set($key, $val);
		}
		return $this->redis->setex($key, $time, $val);
	}

	public function get($key)
	{
		if (!$key) {
			return '';
		}

		return $this->redis->get($key);
	}

	public function sMembers($key) {
		return $this->redis->sMembers($key);
	}
	
//	public function sAdd($key, $val)
//	{
//		return $this->redis->sadd($key, $val);
//	}

//	public function sRem($key, $val)
//	{
//		return $this->redis->srem($key, $val);
//	}

	public function del($key)
	{
		return $this->redis->del($key);
	}

	public function __call($name, $arguments)
	{
		if (count($arguments) != 2) {
			return '';
		}

		$this->redis->$name($arguments[0], $arguments[1]);
	}
}
