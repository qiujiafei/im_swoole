<?php
class Http
{
	CONST HOST = "0.0.0.0";
	CONST PORT = 8811;
	public $http = null;

	public function __construct() {
		$this->http = new swoole_http_server(self::HOST, self::PORT);

		$this->http->set([
			'enable_static_handler' => true,
			'document_root' => "/var/www/html/thinkphp5.1.29/public/static",
			'worker_num' => 4,
			'task_worker_num' => 4,
		 ]);

		$this->http->on("workerstart", [$this, 'onWorkerStart']);
		$this->http->on("request", [$this, 'onRequest']);
		$this->http->on("task", [$this, 'onTask']);
		$this->http->on("finish", [$this, 'onFinish']);
		$this->http->on("close", [$this, 'onClose']);

		$this->http->start();
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

		$_POST['http_server'] = $this->http;
		
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
		$obj = new app\common\Task;
		$method = $data['method'];
		$flag = $obj->$method($data['data']);
		return $flag;
	}

	public function onFinish($serv, $taskId, $data) {
	    echo "taskId:{$taskId}\n";
		echo "finish-data-sucess:{$data}\n";
	}

	public function onClose($ws, $fd) {
	        echo "clientid:{$fd}\n";
	}
}


new Http();
