<?php
namespace app\admin\controller;

use app\common\Util;

class Live
{
	public function push()
	{
		if (empty($_GET)) {
			return Util::show(config('code.error'), 'error');
		}
		$teams = [
			1 => [
				'name' => '马刺',
				'logo' => './imgs/team1.png',
			],
			4 => [
				'name' => '火箭',
				'logo' => './imgs/team2.png',
			]
		];

		$data = [
			'type' => intval($_GET['type']),
			'title' => !empty($teams[$_GET['team_id']]['name']) ? $teams[$_GET['team_id']]['name'] : '直播员',
			'logo' => !empty($teams[$_GET['team_id']]['logo']) ? $teams[$_GET['team_id']]['logo'] : '',
			'content' => !empty($_GET['content']) ? $_GET['content'] : '',
			'image' => !empty($_GET['image']) ? $_GET['image'] : '',
		];
		
		$taskData = [
			'method' => 'pushLive',
			'data' => $data
		];
//	var_dump($_POST['http_server']->connections);	
//		foreach ($_POST['http_server']->connections as $fd) {
//			$_POST['http_server']->push($fd, json_encode($data));
//		}

		$_POST['http_server']->task($taskData);
		return Util::show(config('code.success'), 'success');



//		$clients = Redis::getInstance()->sMembers(config('redis.live_game_key'));
//		if (!empty($clients)) {
//			foreach ($clients as $fd) {
//				$_POST['http_server']->push((int)$fd, 'hello');
//			}
//		}
	}
}
