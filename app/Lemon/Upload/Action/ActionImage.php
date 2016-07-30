<?php namespace App\Lemon\Upload\Action;

use App\Lemon\Repositories\Sour\LmEnv;
use App\Lemon\Repositories\Sour\LmImage;
use App\Lemon\Repositories\System\SysCrypt;
use App\Lemon\Upload\System\SysUpload;
use App\Models\PluginImageKey;
use App\Models\PluginImageUpload;
use Illuminate\Support\MessageBag;
use Intervention\Image\Constraint;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ActionImage {

	private   $error       = '';
	protected $isCheckSign = false;
	protected $accountId   = 0;
	protected $destination = '';

	/**
	 * 保存文件, 保存到某开发者下面
	 * @param UploadedFile $file
	 * @param string       $aim_path 文件存储路径, 不需要填写存储文件夹的目录
	 * @return mixed
	 */
	public function save(UploadedFile $file, $aim_path = '') {
		if (!$this->isCheckSign) {
			return $this->setError('尚未验证上传验签码');
		}
		if ($file->isValid()) {
			// 存储
			$allowedExtensions = [
				'png',
				'jpg',
				'gif',
				'jpeg',   // android default
			];
			if ($file->getClientOriginalExtension() && !in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
				return $this->setError('你只允许上传 "' . implode(',', $allowedExtensions) . '" 格式');
			}

			// 图片存储的磁盘
			$diskName = SysUpload::disk();
			// 磁盘对象
			$Disk = \Storage::disk($diskName);

			if ($aim_path) {
				if (!(strpos($aim_path, '/') === false || strpos($aim_path, '\\') === false)) {
					return $this->setError('不允许在上传根目录存放文件');
				}
				/**
				 * 'dirname' => 'avatar',
				 * 'basename' => '265.png',
				 * 'extension' => 'png',
				 * 'filename' => '265',
				 */
				$pathInfo  = pathinfo($aim_path);
				$extension = $pathInfo['extension'];
				if (!in_array($extension, $allowedExtensions)) {
					$imageExtension = 'png';
				} else {
					$imageExtension = $extension;
				}
				$imageName         = $pathInfo['filename'] . '.' . $imageExtension;
				$imageRelativePath = $pathInfo['dirname'] . '/' . $imageName;
			} else {
				$imageExtension    = $file->getClientOriginalExtension() ?: 'png';
				$imageName         = date('is') . str_random(8) . '.' . $imageExtension;
				$imageRelativePath = date("Ym", time()) . '/' . date("d") . '/' . date("H") . '/' . $imageName;
			}

			$imageContent = file_get_contents($file);
			$Disk->put($imageRelativePath, $imageContent);
			
			/**
			 * 图片的实际存储地址
			 */
			$imageRealPath = disk_path($diskName) . $imageRelativePath;

			// 缩放图片
			if ($file->getClientOriginalExtension() != 'gif') {
				$Image = \Image::make($imageRealPath);
				$Image->resize(1440, null, function (Constraint $constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				});
				$Image->save();
			}
			// check md5
			$md5     = md5_file($imageRealPath);
			$hasItem = PluginImageUpload::where('md5', $md5)->first();
			if ($hasItem) {
				$this->destination = $hasItem->upload_path;
				unlink($imageRealPath);
				return true;
			}

			// 保存图片
			$imageInfo = LmImage::getImageInfo($imageRealPath);
			PluginImageUpload::create([
				'md5'              => $md5,
				'upload_path'      => $imageRelativePath,
				'upload_type'      => 'image',
				'upload_extension' => $file->getClientOriginalExtension(),
				'upload_filesize'  => $imageInfo['size'],
				'upload_mime'      => $imageInfo['mime'],
				'image_type'       => $imageInfo['type'],
				'image_width'      => $imageInfo['width'],
				'image_height'     => $imageInfo['height'],
				'account_id'       => $this->accountId,
			]);
			$this->destination = $imageRelativePath;
			return true;
		} else {
			return $this->setError($file->getErrorMessage());
		}
	}

	public function getDestination() {
		return $this->destination;
	}

	/**
	 * 图片url的地址
	 * @return string
	 */
	public function getUrl() {
		return SysUpload::url($this->destination);
	}

	/**
	 * 验证签名
	 * @param $sign
	 * @return bool
	 */
	public function checkUpload($sign) {

		// 令牌是否存在
		$validator = \Validator::make([
			'sign' => $sign,
		], [
			'sign' => 'required',
		], [
			'sign.required' => '上传令牌不存在',
		]);
		if ($validator->fails()) {
			return $this->setError($validator->errors());
		}

		// 反解令牌
		try {
			$deCode = SysCrypt::decode($sign, config('app.key'));
		} catch (\Exception $e) {
			return $this->setError('令牌解析失败!');
		}
		$info = explode('|', $deCode);

		// 是否是上传令牌
		$isUpload = ($info[0] == 'upload');
		if (!$isUpload) {
			return $this->setError('令牌类型不正确, 应该生成上传令牌!');
		}

		// 令牌失效时间
		$unix_time = $info[3];
		$expires   = config('upload.expires') ?: 3600;
		$diff      = abs(LmEnv::time() - $unix_time);
		if ($expires * 60 < $diff) {
			return $this->setError('上传令牌已过期, 有效期为 `' . config('upload.expires') . '` 分钟');
		}

		// 令牌是否正确, kv 是否相符
		$public = $info[1];  // public key
		$secret = $info[2];  // secret

		if ($public && $secret) {
			$serverSecret = PluginImageKey::getSecretByPublic($public);
			if (!$serverSecret) {
				return $this->setError('令牌不匹配, 冒牌令牌');
			}
		} else {
			return $this->setError('服务器尚未设置访问的密钥');
		}


		$this->accountId   = PluginImageKey::getAccountIdByPublic($public);
		$this->isCheckSign = true;
		return true;
	}

	public function setError($error) {
		// message
		if ($error instanceof MessageBag) {
			$messages = '';
			foreach ($error->all(':message') as $err) {
				$messages .= $err . ',';
			}
			$messages = rtrim($messages, ',');
			$error    = $messages;
		}
		$this->error = $error;
		return false;
	}

	public function getError() {
		return $this->error;
	}
}