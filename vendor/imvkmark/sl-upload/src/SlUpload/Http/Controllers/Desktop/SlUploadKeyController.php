<?php namespace Imvkmark\SlUpload\Http\Controllers\Desktop;

use App\Http\Controllers\Desktop\InitController as DesktopInitController;
use App\Http\Requests;
use App\Models\PamAccount;
use Illuminate\Http\Request;
use Imvkmark\SlUpload\Models\SlImageKey;

/**
 * 开发者图片平台 key 管理
 * Class ImageKeyController
 * @package App\Http\Controllers\Desktop
 */
class SlUploadKeyController extends DesktopInitController {

	public function __construct(Request $request) {
		parent::__construct($request);
		$this->middleware('lm_desktop.auth');
	}

	public function getIndex() {
		$items = SlImageKey::paginate($this->pageNum);
		return view('sl-upload::desktop.image_key.index', [
			'items' => $items,
		]);
	}

	public function getCreate() {
		$developers = PamAccount::where('account_type', PamAccount::ACCOUNT_TYPE_DEVELOP)
			->lists('account_name', 'account_id');
		$public     = SlImageKey::genPublic($this->pam->account_id);
		return view('sl-upload::desktop.image_key.item', [
			'develops'         => $developers,
			'key_public' => $public,
		]);
	}

	public function postCreate(Request $request) {
		$key    = SlImageKey::genPublic($this->pam->account_id);
		$exists = SlImageKey::where('account_id', $request->input('account_id'))
			->where('key_public', $key)
			->exists();
		if ($exists) {
			return site_end('error', '此用户已经存在这个key, 不得重复添加', '', $request->all());
		} else {
			$data = [
				'key_type'   => $request->input('key_type'),
				'key_public' => $key,
				'key_secret' => $request->input('key_secret'),
				'account_id'       => $request->input('account_id'),
			];
			SlImageKey::create($data);
			return site_end('success', '添加成功!', 'location|' . route('dsk_image_key.index'));
		}
	}

	public function getEdit($id) {
		/** @type SlImageKey $item */
		$item       = SlImageKey::find($id);
		$developers = PamAccount::where('account_type', PamAccount::ACCOUNT_TYPE_DEVELOP)
			->lists('account_name', 'account_id');
		return view('sl-upload::desktop.image_key.item', [
			'item'             => $item,
			'key_public' => $item->key_public,
			'develops'         => $developers,
		]);
	}

	public function postEdit(Request $request, $id) {
		SlImageKey::where('id', $id)->update($request->except(['_token']));
		return site_end('success', '保存成功', 'location|' . route('dsk_image_key.index'));
	}

	public function postDestroy($id) {
		SlImageKey::destroy($id);
		return site_end('success', '删除成功!', 'location|' . route('dsk_image_key.index'));
	}

}
