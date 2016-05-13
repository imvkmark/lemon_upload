### 加载本扩展
在 `config/app.php` 的 `providers` 部分加入
```
Imvkmark\SlUpload\SlUploadServiceProvider::class
```

### 生成配置
- 配置config
如果是需要强制生成配置, 在后边加入 `--force` 选项
```
php artisan vendor:publish --tag=sour-lemon
```
- 配置 acl
在 `App/Lemon/Suit/Acl/desktop.php` 的 `group` 字段中加入 `dsk_image_key`, 让其显示在后台的菜单列表中

### 生成数据库配置
生成需要整合的数据表
```
$ php artisan lemon:upload_migration

Tables: plugin_image_key, plugin_image_key
A migration that creates 'plugin_image_key', 'plugin_image_key' tables will be created in database/migrations directory

Proceed with the migration creation? [Yes|no] (yes/no) [yes]:
> yes

Creating migration...
Migration successfully created!
```

### 数据库合并
```
$ php artisan migrate

Migrated: 2016_04_10_185031_sl-upload_setup_tables
```

### 加入插件路由上传
将路由放置在 `App/Http/Routes/vendor.php`
```
Route::post('upload_image', [
	'as'   => 'vendor.upload_image',
	'uses' => '\Imvkmark\SlUpload\Http\Controllers\SlUploadController@postImage@postImage',
]);
```
### 后台分配 public_key 和 密钥


### 将密钥填入 `sl-upload.php`

### 禁止token 对上传进行校验

```
class VerifyCsrfToken extends BaseVerifier {

	/**
	 * The URIs that should be excluded from CSRF verification.
	 * @var array
	 */
	protected $except = [
		// other ignore
		'upload_image'
	];
}
```
### 测试上传

