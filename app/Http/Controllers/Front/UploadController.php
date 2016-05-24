<?php namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Lemon\Upload\Action\ActionImage;
use Illuminate\Http\Request;


/**
 * 服务接口
 * Class UploadController
 * @package App\Http\Controllers\Support
 */
class UploadController extends Controller {

	protected $action;

	/**
	 * 图片上传组件的后端
	 * @param Request $request
	 *    'kindeditor' => 'imgFile',
	 *    'avatar'     => '__avatar1',
	 *    'default'    => 'image_file',
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postImage(Request $request) {
		$field     = $request->input('field', 'image_file');
		$validator = \Validator::make($request->all(), [
			$field         => 'required',
			'upload_token' => 'required',
		], [
			$field . '.required'    => '图片参数不能为空',
			'upload_token.required' => 'upload_token不能为空',
		]);
		if ($validator->fails()) {
			return site_end('error', $validator->errors(), [
				'json' => true,
			]);
		}

		$sign = $request->input('upload_token');

		// 匹配
		$file  = \Input::file($field);
		$Image = new ActionImage();
		if ($Image->checkUpload($sign) && $Image->save($file)) {
			return site_end('success', '图片上传成功', [
				'json'        => true,
				'success'     => true,   // 兼容 fullAvatarEditor
				'url'         => $Image->getUrl(),
				'destination' => $Image->getDestination(),
			]);
		} else {
			return site_end('error', $Image->getError(), [
				'json' => true,
			]);
		}
		// kindeditor
		// {"error" : 0,"url" : "' . $url . '"}
		// avatar
		// update avatar
		// thumb
		// url, path
	}

}
