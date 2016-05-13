<?php namespace System;


use App\Lemon\Repositories\System\SysAcl;
use App\Models\PamAccount;

class RbacTest extends \TestCase {

	public function testAcl() {
		$acl = SysAcl::permission(PamAccount::ACCOUNT_TYPE_DESKTOP);
		var_export($acl);
	}
}
