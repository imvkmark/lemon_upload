<?php namespace App\Http\Controllers\Desktop;

use App\Http\Requests;

use App\Lemon\Repositories\Application\SettingUi;
use App\Lemon\Repositories\System\SysAcl;
use App\Models\BaseConfig;
use App\Models\PluginArea;
use Illuminate\Http\Request;


/**
 * 网站设置
 * Class SiteController
 * @package App\Http\Controllers\Desktop
 */
class SiteController extends InitController {

	public function __construct(Request $request) {
		parent::__construct($request);
		$this->middleware('lm_desktop.auth');
	}

	public function getSetting() {
		$Ui = new SettingUi('site');
		$Ui->setDesktop();
		$Ui->setTitle('网站设置');
		$site = site();
		return $Ui->render($site);
	}

	public function postSetting(Request $request) {
		BaseConfig::configUpdate($request->except(['_token']), 'site');
		BaseConfig::reCache();
		return site_end('success', '更新配置成功', 'location|' . route('dsk_site.setting'));
	}

	/**
	 * 更新缓存
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function getCache() {
		BaseConfig::reCache();
		PluginArea::reCache();
		SysAcl::reCache();
		return site_end('success', '更新缓存成功', 'location|' . route('dsk_home.tip'));
	}
}