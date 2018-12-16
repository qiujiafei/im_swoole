<?php
namespace app\index\controller;

use app\common\Util;

class Chart
{
	public function index()
	{
		if (empty($_POST['game_id'])) {
			return Util::show(config('code.error'), 'not found game_id');
		}
		
		if (empty($_POST['content'])) {
			return Util::show(config('code.error'), 'not found content');
		}

		if (empty($_POST['nick_name'])) {
			return Util::show(config('code.error'), 'not found nick_name');
		}

		$data = [
			'user' => $_POST['nick_name'],
			'content' => $_POST['content']
		];
		foreach ($_POST['http_server']->ports[1]->connections as $fd) {
			$_POST['http_server']->push($fd, json_encode($data));
		}			
		return Util::show(config('code.success'), 'ok', $data);
	}

}
