<?php namespace App\Console\Commands\Lemon;
/**
 * rbac
 * ---- 初始化
 * php artisan lemon:rbac init --type="desktop"
 * 
 * @author     Mark <zhaody901@126.com>
 * @copyright  Copyright (c) 2013-2016 Sour Lemon Team
 */
use App\Lemon\Repositories\System\SysAcl;
use App\Models\PamAccount;
use App\Models\PamPermission;
use App\Models\PamRole;
use Illuminate\Console\Command;

/**
 * Class Rbac
 * @package App\Console\Commands\Lemon
 */
class Rbac extends Command {

	/**
	 * 前端部署.
	 * @var string
	 */
	protected $signature = 'lemon:rbac 
		{do : actions in rbac}
		{--type= : the type need init}
		{--permission= : the permission need check}
		';

	/**
	 * 描述
	 * @var string
	 */
	protected $description = 'rbac auth init handler.';


	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle() {

		$do = $this->argument('do');
		switch ($do) {
			// lemon:rbac init --type=desktop
			case 'init':
				$type = $this->option('type');
				$this->init($type);
				break;
			case 'check';
				$permission = $this->option('permission');
				$this->checkPermission($permission);
				break;
			default:
				$this->error('Please type right action![init|check]');
				break;
		}

	}

	private function checkPermission($permission) {
		if (PamPermission::where('permission_name', $permission)->exists()) {
			$this->info('Permission `' . $permission . '` In Store ');
		} else {
			$this->error('Permission `' . $permission . '` Not In Store, Please Add To `Lemon/Suit/Acl/` and run `lemon:rbac init --type=xxx` command');
		}
	}

	private function init($type) {
		$accountTypes = PamAccount::accountTypeAll();
		if (!array_key_exists($type, $accountTypes)) {
			$this->error('Account type `' . $type . '` not available!');
		}

		// get all permission
		$permission = SysAcl::permission($type);

		// db permission
		$exists = PamPermission::where('account_type', $type)->lists('permission_name')->toArray();

		// out of date permission drop
		$needDrop = array_diff($exists, array_keys($permission));
		if ($needDrop) {
			PamPermission::where('account_type', $type)->whereIn('permission_name', $needDrop)->delete();
		}

		// insert db
		foreach ($permission as $route => $value) {
			PamPermission::updateOrCreate([
				'permission_name' => $route,
			], [
				'permission_name'        => $route,
				'permission_title'       => $value['title'],
				'permission_group'       => $value['group_title'],
				'is_menu'                => (int) $value['menu'],
				'account_type'           => $type,
				'permission_description' => '',
			]);
		}
		$this->info('Import Rbac permission of `' . $type . '` success!');

		if ($type == PamAccount::ACCOUNT_TYPE_DESKTOP) {
			// 给 root 赋予所有权限
			$this->info('');
			$root        = PamRole::where('role_name', 'root')->first();
			$permissions = PamPermission::where('account_type', PamAccount::ACCOUNT_TYPE_DESKTOP)->get();
			$root->savePermissions($permissions);
			$root->cachedPermissions();
			$this->info('Save All Rbac Permission To  `root` Group!');
		}
	}
}
