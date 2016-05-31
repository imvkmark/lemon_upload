<?php
use App\Lemon\Repositories\Sour\LmArr;
use App\Lemon\Repositories\Sour\LmStr;
use App\Lemon\Repositories\Sour\LmUtil;
use \App\Lemon\Repositories\System\SysAcl;
use App\Models\BaseConfig;
use Illuminate\Support\MessageBag;

/**
 * 检测用户id 是否超级管理员, 超级管理员配置项在 config/lemon.super_role_id
 * @param $role_id int 角色ID
 * @return bool
 */
function is_super($role_id) {
	return config('lemon.super_role_id') == $role_id;
}


/**
 * 返回联系方式的正常连接
 * @param $account
 * @param $type
 * @return string
 */
function im($account, $type) {
	switch ($type) {
		case 'qq':
			return "<a href=\"Tencent://Message/?Uin={$account}\"><i class=\"fa fa-qq\"></i>{$account}</a>";
			break;
		case 'm':
		case 'mobile':
			return "<i class=\"fa fa-mobile fa-lg\"></i>{$account}</a>";
			break;
		default:
			return $account;
	}
}

/**
 * 获取磁盘的真实路径
 * @param string $disk
 * @return mixed
 */
function disk_path($disk = '') {
	if (!$disk) {
		$disk = Storage::getDefaultDriver();
	}
	return Storage::disk($disk)->getDriver()->getAdapter()->getPathPrefix();
}

/**
 * 酸柠檬核心框架的位置
 * @param string $path
 * @return string
 */
function lemon_path($path = '') {
	return app_path('/Lemon/' . $path);
}

/**
 * 隐藏联系方式
 * @param $input
 * @return mixed|string
 */
function hide_contact($input) {
	return \App\Lemon\Repositories\Sour\LmStr::hideContact($input);
}

/**
 * 隐藏邮箱
 * @param $input
 * @return mixed|string
 */
function hide_email($input) {
	return \App\Lemon\Repositories\Sour\LmStr::hideEmail($input);
}



/**
 * 获取完整的数据表名称
 * @param $table
 * @return string
 */
function table($table) {
	$prefix = \DB::connection()->getTablePrefix();
	if (substr($table, 0, strlen($prefix)) == $prefix) {
		return $table;
	} else {
		return $prefix . $table;
	}
}

/**
 * 自定义可以传值的路由写法
 * @param       $route
 * @param array $route_params
 * @param array $params
 * @return string
 */
function route_url($route, $route_params = [], $params = []) {
	if (is_null($route_params)) {
		$route_params = [];
	}
	$route_url = route($route, $route_params);
	return $route_url . (!empty($params) ? '?' . http_build_query($params) : '');
}

/**
 * api 调用返回值方式
 * validator : api_end('error', $validator->errors())
 * string    : api_end('error', '这里放置错误信息')
 * string    : api_end('success', '这里放置成功信息')
 * string    : api_end(trans('api_front.some_code_with'))
 * @param        $api_str
 * @param string $append
 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
 */
function api_end($api_str, $append = '') {
	// message
	if ($append instanceof MessageBag) {
		$messages = '';
		foreach ($append->all(':message') as $message) {
			$messages .= $message . ',';
		}
		$messages = rtrim($messages, ',');
		$api_str  = substr_replace(trans('api_front.request_error'), $messages, (strpos(trans('api_front.request_error'), '|') + 1));
	}

	if ($api_str == 'success' || $api_str == 'error') {
		if ($api_str == 'success') {
			$api_str = trans('api_front.request_success');
		}
		if ($api_str == 'error') {
			$api_str = trans('api_front.request_error');
		}

		// 第一个参数是 success / error, 第二个参数是错误内容且不存在 `|` 返回相关值
		if (is_string($append)) {
			if (strpos($append, '|') === false) {
				$api_str = substr_replace($api_str, $append, (strpos($api_str, '|') + 1));
			}
		}
	}

	if (is_array($api_str)) {
		$api_str = trans('api_front.request_success');
	}
	$apiArr = explode('|', $api_str);
	$return = [];

	$successStr = trans('api_front.request_success');
	$successArr = explode('|', $successStr);

	$return['code']    = isset($apiArr[0]) ? $apiArr[0] : $successArr[0];
	$return['message'] = isset($apiArr[1]) ? $apiArr[1] : $successArr[1];


	$data = LmStr::parseKey($append);
	if ($data) {
		$return['data'] = array_merge($data);
	}
	if (env('APP_ENV') == 'local') {
		\Log::info($return);
	}
	return response($return);
}

function api_url($route, $version = '') {
	if (!$version) {
		$version = config('api.version');
	}
	return app('Dingo\Api\Routing\UrlGenerator')->version($version)->route($route);
}

/**
 * 支援信息
 * @param string $type
 * @param        $support_info
 * @return \Illuminate\Http\JsonResponse
 */
function support_end($type = 'success', $support_info) {
	if (!in_array($type, ['error', 'success'])) {
		$type = 'success';
	}
	if ($support_info instanceof MessageBag) {
		$messages = '';
		foreach ($support_info->all(':message') as $message) {
			$messages .= $message . ',';
		}
		$messages = rtrim($messages, ',');
		return \Response::json([
			'status'  => $type,
			'message' => $messages,
		]);
	}
	if (is_string($support_info)) {
		return \Response::json([
			'status'  => $type,
			'message' => $support_info,
		]);
	}

	if (is_array($support_info)) {
		return \Response::json($support_info);
	}

	return '';
}

/**
 * 错误输出
 * @param              $type
 * @param string       $message
 * @param string       $append
 *                            json: 以json 数据返回
 *                            forget : 不将错误信息返回到session 中
 *                            location : 重定向
 *                            reload : 刷新页面
 *                            time   : 刷新或者重定向的时间(毫秒), 如果不填写, 默认为立即刷新或者重定向
 *                            reload_opener : 刷新母窗口
 * @param array        $input 表单提交的数据, 是否连带返回
 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
 */
function site_end($type, $message = '', $append = '', $input = []) {

	$appendArr = LmStr::parseKey($append);

	// is json
	$isJson = false;
	if (isset($appendArr['json'])) {
		$isJson = true;
		unset($appendArr['json']);
	}

	// is forget
	$isForget = false;
	if (isset($appendArr['forget'])) {
		$isForget = true;
		unset($appendArr['forget']);
	}

	// message
	if ($message instanceof MessageBag) {
		if (\Request::ajax() || $isJson) {
			$messages = '';
			foreach ($message->all(':message') as $message) {
				$messages .= $message . ',';
			}
			$messages = rtrim($messages, ',');
		} else {
			$messages = '<ul>';
			foreach ($message->all('<li>:message</li>') as $message) {
				$messages .= $message;
			}
			$messages .= '</ul>';
		}
		$message = $messages;
	}

	$append   = LmArr::genKey($appendArr);
	$location = isset($appendArr['location']) ? $appendArr['location'] : '';
	$time     = isset($appendArr['time']) ? $appendArr['time'] : 0;

	$funEndView = function ($time, $location, $input) {
		if ($time || $location == 'back') {
			$re = $location ?: 'back';
			return view('lemon.template.message', [
				'location' => $re,
				'input'    => $input,
				'time'     => isset($appendArr['time']) ? $appendArr['time'] : 0,
			]);
		} else {
			$re = $location ? \Redirect::to($location) : \Redirect::back();
			return $input ? $re->withInput($input) : $re;
		}
	};

	$funSplash = function ($type = 'success', $message = '', $append = '', $input = []) {
		$data = LmUtil::genSplash($type, $message, $append);
		\Session::flashInput($input);
		return \Response::json($data);
	};


	// success
	if ($type === true || $type == 'success' || $type == 'true') {
		if (!$isForget) {
			\Session::flash('end.message', $message);
			\Session::flash('end.level', 'success');
		}
		if (\Request::ajax() || $isJson) {
			return $funSplash('success', $message, $append, $input);
		} else {
			return $funEndView($time, $location, $input);
		}
	} else {
		if (!$isForget) {
			\Session::flash('end.message', $message);
			\Session::flash('end.level', 'danger');
		}
		if (\Request::ajax() || $isJson) {
			return $funSplash('error', $message, $append, $input);
		} else {
			return $funEndView($time, $location, $input);
		}
	}
}

/**
 * 检测命令是否存在
 * @param $cmd
 * @return bool
 */
function command_exist($cmd) {
	$returnVal = shell_exec("which $cmd");
	return (empty($returnVal) ? false : true);
}

/**
 * 发送trace 邮件来跟踪错误
 * @param        $subject
 * @param string $message
 */
function mail_trace($subject, $message = '') {
	if (!$message) {
		$message = $subject;
	}
	\Mail::queue('lemon.email.trace', ['info' => $message], function (\Illuminate\Mail\Message $mail) use ($subject) {
		$mail->to('trace@ixdcw.com');
		$mail->subject($subject);
	});
}

/**
 * 获取markdown 索引
 * @param $file
 * @return string
 */
function markdown_toc($file) {
	// ensure using only "\n" as line-break
	$source  = str_replace(["\r\n", "\r"], "\n", $file);
	$raw_toc = [];
	// look for markdown TOC items
	preg_match_all(
		'/^(?:=|-|#).*$/m',
		$source,
		$matches,
		PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE
	);

	// preprocess: iterate matched lines to create an array of items
	// where each item is an array(level, text)
	$file_size = strlen($source);
	foreach ($matches[0] as $item) {
		$found_mark = substr($item[0], 0, 1);
		if ($found_mark == '#') {
			// text is the found item
			$item_text  = $item[0];
			$item_level = strrpos($item_text, '#') + 1;
			$item_text  = substr($item_text, $item_level);
		} else {
			// text is the previous line (empty if <hr>)
			$item_offset      = $item[1];
			$prev_line_offset = strrpos($source, "\n", -($file_size - $item_offset + 2));
			$item_text        =
				substr($source, $prev_line_offset, $item_offset - $prev_line_offset - 1);
			$item_text        = trim($item_text);
			$item_level       = $found_mark == '=' ? 1 : 2;
		}
		if (!trim($item_text) OR strpos($item_text, '|') !== FALSE) {
			// item is an horizontal separator or a table header, don't mind
			continue;
		}
		$raw_toc[] = ['level' => $item_level, 'text' => trim($item_text)];
	}

	// create a JSON list (the easiest way to generate HTML structure is using JS)
	return $raw_toc;
}

/**
 * 获取 access_token
 * @param \Illuminate\Http\Request $request
 * @return array|string
 */
function api_access_token(\Illuminate\Http\Request $request) {
	$token = $request->header('X-ACCESS-TOKEN');
	if (!$token) {
		$token = $request->input('access_token');
	}
	return $token;
}


/**
 * 公共文件夹的地址
 * @param string $path
 * @param array  $params
 * @return mixed|string
 */
function public_url($path = '', $params = []) {
	$url = config('app.url');
	if ($path) {
		if (substr($path, 0, 1) != '/') {
			$url .= '/';
		}
		$url .= $path;
	}
	if ($params) {
		$url .= '?' . http_build_query($params);
	}
	return $url;
}


/**
 * 缓存前缀生成
 * @param        $class
 * @param string $suffix
 * @return string
 */
function cache_name($class, $suffix = '') {
	$snake = str_replace('\\', '', snake_case(lcfirst($class)));
	return $suffix ? $snake . '_' . $suffix : $snake;
}