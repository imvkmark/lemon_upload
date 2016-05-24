<?php namespace App\Http\Controllers\Desktop;

use App\Http\Controllers\Desktop\InitController as DesktopInitController;
use App\Http\Requests;
use App\Models\PamAccount;
use App\Models\PluginImageKey;
use Illuminate\Http\Request;

/**
 * 开发者图片平台 key 管理
 * Class ImageKeyController
 * @package App\Http\Controllers\Desktop
 */
class ImageKeyController extends DesktopInitController {

	public function __construct(Request $request) {
		parent::__construct($request);
		$this->middleware('lm_desktop.auth');
	}

	public function getIndex() {
		$items = PluginImageKey::paginate($this->pageNum);
		return view('desktop.image_key.index', [
			'items' => $items,
		]);
	}

	public function getCreate() {
		$developers = PamAccount::where('account_type', PamAccount::ACCOUNT_TYPE_DEVELOP)
			->lists('account_name', 'account_id');
		$public     = PluginImageKey::genPublic($this->pam->account_id);
		return view('desktop.image_key.item', [
			'develops'   => $developers,
			'key_public' => $public,
		]);
	}

	public function postCreate(Request $request) {
		$key    = PluginImageKey::genPublic($this->pam->account_id);
		$exists = PluginImageKey::where('account_id', $request->input('account_id'))
			->where('key_public', $key)
			->exists();
		if ($exists) {
			return site_end('error', '此用户已经存在这个key, 不得重复添加', '', $request->all());
		} else {
			$data = [
				'key_type'   => $request->input('key_type'),
				'key_public' => $key,
				'key_secret' => $request->input('key_secret'),
				'account_id' => $request->input('account_id'),
			];
			PluginImageKey::create($data);
			return site_end('success', '添加成功!', 'location|' . route('dsk_image_key.index'));
		}
	}

	public function getEdit($id) {
		/** @type PluginImageKey $item */
		$item       = PluginImageKey::find($id);
		$developers = PamAccount::where('account_type', PamAccount::ACCOUNT_TYPE_DEVELOP)
			->lists('account_name', 'account_id');
		return view('desktop.image_key.item', [
			'item'       => $item,
			'key_public' => $item->key_public,
			'develops'   => $developers,
		]);
	}

	public function postEdit(Request $request, $id) {
		PluginImageKey::where('id', $id)->update($request->except(['_token']));
		return site_end('success', '保存成功', 'location|' . route('dsk_image_key.index'));
	}

	public function postDestroy($id) {
		PluginImageKey::destroy($id);
		return site_end('success', '删除成功!', 'location|' . route('dsk_image_key.index'));
	}

}
