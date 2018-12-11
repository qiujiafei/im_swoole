<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 18/2/28
 * Time: 上午1:39
 */
$http = new swoole_http_server("0.0.0.0", 8811);

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/var/www/html/thinkphp5.1.29/public/static",
    ]
);
$http->on('WorkerStart', function(swoole_server $server, $worker_id){
    //
    require __DIR__ . '/../thinkphp/base.php';
});
$http->on('request', function($request, $response) {
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
	ob_start();
    try {
    	think\Container::get('app')->run()->send();
    }catch (\Exception $e) {
        //todo
    }
	$res = ob_get_contents();
	if ($res) {
		ob_end_clean();
		$response->end($res);
	}
	$response->end('ai');
});

$http->start();
