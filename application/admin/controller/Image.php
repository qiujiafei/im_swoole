<?php
namespace app\admin\controller;

use app\common\Util;

class Image
{
	public function index(){
		if (!$_FILES['file']) {
			return Util::show(config(code.error), 'image upload fail');
		}
		$file = $_FILES['file'];
		if ($file["error"] > 0) {
		    return Util::show(config('code.error'), 'error');
		} 
		try {
			$tmp = explode('.', $file['name']);
			$fileName = date("YmdHis", time()).".".array_pop($tmp);
			$data['image'] = __DIR__ . "/../../../public/static/upload/" . $fileName;
			move_uploaded_file($file["tmp_name"], $data['image']);
			$imgUrl = config('upload.path'). "upload/" .$fileName;
		} catch (\Exception $e) {
			return Util::show(config('code.error'). 'error', $e->getMessage());
		}
		return Util::show(config('code.success'), 'OK', $imgUrl);
	}

}
