<?php namespace App\Http\Controllers\Desktop;

use App\Http\Controllers\Desktop\InitController as DesktopInitController;
use App\Http\Requests;
use App\Models\PamAccount;
use App\Models\PluginImageKey;
use App\Models\PluginImageUpload;
use Illuminate\Http\Request;

/**
 * 开发者图片平台 key 管理
 * Class ImageKeyController
 * @package App\Http\Controllers\Desktop
 */
class ImageUploadController extends DesktopInitController {

	public function __construct(Request $request) {
		parent::__construct($request);
		$this->middleware('lm_desktop.auth');
	}

	public function getIndex() {
		$items = PluginImageUpload::paginate($this->pageNum);
		return view('desktop.image_upload.index', [
			'items' => $items,
		]);
	}

	public function postDestroy($id) {
		PluginImageUpload::destroy($id);
		return site_end('success', '删除成功!', 'location|' . route('dsk_image_upload.index'));
	}

}
