<?php
class Ws
{
	CONST HOST = "0.0.0.0";
	CONST PORT = 8811;
	public $ws = null;

	public function __construct() {
		$this->ws = new swoole_websocket_server(self::HOST, self::PORT);

		$this->ws->set([
			'enable_static_handler' => true,
			'document_root' => "/var/www/html/thinkphp5.1.29/public/static",
			'worker_num' => 4,
			'task_worker_num' => 4,
		 ]);
		
		$this->ws->on("open", [$this, 'onOpen']);
		$this->ws->on("message", [$this, 'onMessage']);
		$this->ws->on("workerstart", [$this, 'onWorkerStart']);
		$this->ws->on("request", [$this, 'onRequest']);
		$this->ws->on("Task", [$this, 'onTask']);
		$this->ws->on("Finish", [$this, 'onFinish']);
		$this->ws->on("close", [$this, 'onClose']);

		$this->ws->start();
	}

	public function onWorkerStart($server,  $worker_id) {
	    // 定义应用目录
		require __DIR__ . '/../thinkphp/base.php';
	}

	public function onRequest($request, $response) {
		//$_SERVER  =  [];
		if (isset($request->server)) {
			foreach ($request->server as $k => $v) {
				$_SERVER[strtoupper($k)] = $v;
			}
		}
		if (isset($request->header)) {
			foreach ($request->header as $k => $v) {
				$_SERVER[strtoupper($k)] = $v;
			}
		}

		$_GET = [];
		if (isset($request->get)) {
			foreach ($request->get as $k => $v) {
				$_GET[$k] = $v;
			}
		}
		
		$_POST = [];
		if (isset($request->post)) {
			foreach ($request->post as $k => $v) {
				$_POST[$k] = $v;
			}
		}

		$_POST['http_server'] = $this->ws;
		
		ob_start();
		try {
			think\Container::get('app')->run()->send();
		} catch (\Exception $e) {
			//todo
		}
		$res = ob_get_contents();
		if ($res) {
			ob_end_clean();
			$response->end($res);
		} else {
			$response->end('ai');
		}
	}

	public function onTask($serv, $taskId, $workerId, $data) {
	    // 分发 task 任务机制，让不同的任务 走不同的逻辑
		require_once __DIR__ . '/../thinkphp/base.php';
		think\Container::get('app')->run()->send();
		$obj = new app\common\Task;
		$method = $data['method'];
		$flag = $obj->$method($data['data']);
		return $taskId;
	}

	public function onFinish($serv, $taskId, $data) {
	    echo "taskId:{$taskId}\n";
		echo "finish-data-sucess:{$data}\n";
	}

	public function onClose($ws, $fd) {
	        echo "clientid:{$fd}\n";
	}

	/**
	* 监听ws连接
	* @param $ws
	* @param $request
	*/
	public function onOpen($ws, $request) {
		// fd redis [1]
		\app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.live_game_key'), $request->fd);
		var_dump($request->fd);
	}

	/**
	* 监听ws消息事件
	* @param $ws
	* @param $frame
	*/
	public function onMessage($ws, $frame) {
		echo "ser-push-message:{$frame->data}\n";
		$ws->push($frame->fd, "server-push:".date("Y-m-d H:i:s"));
	}
}


new Ws();
