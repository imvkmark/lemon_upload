<?php namespace Imvkmark\L5Thumber\Eva;

/**
 * Parse Url as EvaThumber necessary parts
 * - Example : http://localhost/EvaThumber/thumb/zip/archive/zipimage,w_100.jpg?query=123
 * Will be parse to :
 * -- scheme : http
 * -- host : localhost
 * -- query : query=123
 * -- urlScriptName : /EvaThumber/index.php
 * -- urlRewritePath : /EvaThumber
 * -- urlPrefix : thumb
 * -- urlKey : zip
 * -- urlImagePath : /thumb/zip/archive/zipimage,w_100.jpg
 * -- urlImageName : zipimage,w_100.jpg
 * -- urlRewriteEnabled : true
 * -- imagePath : /archive
 * -- imageName : zipimage.jpg
 */
class Url {

	/**
	 * @var string
	 */
	protected $scheme;

	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var string
	 */
	protected $port;

	/**
	 * @var string
	 */
	protected $query;

	/**
	 * @var string Original URL
	 */
	protected $urlString;

	/**
	 * @var string
	 */
	protected $urlPath;


	/**
	 * @var string
	 */
	protected $urlScriptName;

	/**
	 * @type string
	 */
	protected $route;

	/**
	 * @var string
	 */
	protected $urlImagePath;

	/**
	 * @var string
	 */
	protected $urlImageName;

	/**
	 * @var boolean
	 */
	protected $urlRewriteEnabled;

	/**
	 * @var string
	 */
	protected $urlRewritePath;

	/**
	 * @var string
	 */
	protected $imagePath;

	/**
	 * @var string
	 */
	protected $imageName;

	protected $config;

	/**
	 * 配置键
	 * @type
	 */
	protected $configKey;

	public function toArray() {
		return [
			'urlString'         => $this->urlString,
			'urlPath'           => $this->getUrlPath(),
			'scheme'            => $this->getScheme(),
			'host'              => $this->getHost(),
			'query'             => $this->getQuery(),
			'urlScriptName'     => $this->getUrlScriptName(), //from $_SERVER
			'urlRewritePath'    => $this->getUrlRewritePath(),
			'configKey'         => $this->getConfigKey(),
			'urlKey'            => $this->getRoute(),
			'urlImagePath'      => $this->getUrlImagePath(),
			'urlImageName'      => $this->getUrlImageName(),
			'urlRewriteEnabled' => $this->getUrlRewriteEnabled(),
			'imagePath'         => $this->getImagePath(),
			'imageName'         => $this->getImageName(),
		];
	}

	public function getScheme() {
		return $this->scheme;
	}

	public function getHost() {
		return $this->host;
	}

	public function getPort() {
		return $this->port;
	}

	public function getQuery() {
		return $this->query;
	}

	public function getUrlString() {
		return $this->urlString;
	}

	public function getUrlRewriteEnabled() {
		if ($this->urlRewriteEnabled !== null) {
			return $this->urlRewriteEnabled;
		}

		$urlPath = $this->getUrlPath();
		if (false === strpos($urlPath, '.php')) {
			return $this->urlRewriteEnabled = true;
		}
		return $this->urlRewriteEnabled = false;
	}

	public function getUrlPath() {
		if ($this->urlPath) {
			return $this->urlPath;
		}

		if (!$this->urlString) {
			return '';
		}

		$url = $this->urlString;
		$url = parse_url($url);
		return $this->urlPath = isset($url['path']) ? $url['path'] : '';
	}

	/**
	 * 获取路由
	 * /{route}/{config}/demo.jpg
	 * @return string
	 */
	public function getRoute() {
		$urlImagePath      = $this->getUrlImagePath();
		$urlImagePathArray = explode('/', ltrim($urlImagePath, '/'));
		if (count($urlImagePathArray) < 2) {
			return '';
		}
		return $this->route = $urlImagePathArray[0];
	}

	/**
	 * 配置键
	 * @return string
	 */
	public function getConfigKey() {
		$urlImagePath      = $this->getUrlImagePath();
		$urlImagePathArray = explode('/', ltrim($urlImagePath, '/'));
		if (count($urlImagePathArray) < 2) {
			return '';
		}
		return $this->configKey = $urlImagePathArray[1];
	}

	public function setUrlScriptName($urlScriptName) {
		$this->urlScriptName = (string) $urlScriptName;
		return $this;
	}

	public function getUrlScriptName() {
		if ($this->urlScriptName) {
			return $this->urlScriptName;
		}

		if (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME']) {
			$scriptName = $_SERVER['SCRIPT_NAME'];

			if (false === strpos($scriptName, '.php')) {
				return $this->urlScriptName = '';
			}


			//Nginx maybe set SCRIPT_NAME as full url path
			if (($scriptNameEnd = substr($scriptName, -4)) && $scriptNameEnd === '.php') {
				$scriptNameArray = explode('/', $scriptName);
				array_shift($scriptNameArray); //remove start slash
				array_pop($scriptNameArray);
				$scriptNameFront = implode('/', $scriptNameArray);

				//not find server script_name in url, drop script_name, because script_name maybe not correct
				if ($scriptNameFront && $this->urlString && false === strpos($this->urlString, $scriptNameFront)) {
					return $this->urlScriptName = '';
				}
				return $this->urlScriptName = $scriptName;
			} else {
				$scriptNameArray = explode('/', $scriptName);
				$scriptName      = [];
				foreach ($scriptNameArray as $scriptNamePart) {
					$scriptName[] = $scriptNamePart;
					if (false !== strpos($scriptNamePart, '.php')) {
						break;
					}
				}
				return $this->urlScriptName = implode('/', $scriptName);
			}
		}

		return '';
	}

	public function getUrlImagePath() {

		if ($this->urlImagePath) {
			return $this->urlImagePath;
		}

		$urlPath = $this->getUrlPath();
		if (!$urlPath) {
			return '';
		}

		$urlScriptName = $this->getUrlScriptName();

		if ($urlScriptName) {
			$urlRewriteEnabled = $this->getUrlRewriteEnabled();
			if ($urlRewriteEnabled) {
				return $this->urlImagePath = str_replace($this->getUrlRewritePath(), '', $urlPath);
			} else {
				return $this->urlImagePath = str_replace($urlScriptName, '', $urlPath);
			}
		} else {
			return $this->urlImagePath = $urlPath;
		}
	}

	public function getUrlImageName() {
		if ($this->urlImageName) {
			return $this->urlImageName;
		}

		$urlImagePath = $this->getUrlImagePath();
		if (!$urlImagePath) {
			return $this->urlImageName = '';
		}

		$urlImagePathArray = explode('/', $urlImagePath);
		$urlImageName      = array_pop($urlImagePathArray);

		//urlImageName must have extension part
		$urlImageNameArray = explode('.', $urlImageName);
		$urlImageNameCount = count($urlImageNameArray);
		if ($urlImageNameCount < 2 || !$urlImageNameArray[$urlImageNameCount - 1]) {
			return $this->urlImageName = '';
		}

		// url with class
		if (is_object($this->config) && isset($this->config->class_separator) && strpos(
				$urlImageName,
				$this->config->class_separator
			) !== false
		) {
			list($urlImageName, $className) = explode($this->config->class_separator, $urlImageName);
			if (!isset($this->config->classes->$className)) {
				throw new Exception\InvalidArgumentException(
					sprintf(
						'class [%s] has not been set',
						$className
					)
				);
			}

			$_urlNameArr  = explode('.', $urlImageName);
			$fileExt      = array_pop($_urlNameArr);
			$urlImageName = implode('.', $_urlNameArr) . ',' . $this->config->classes->$className . '.' . $fileExt;
		}

		return $this->urlImageName = $urlImageName;
	}

	public function setUrlImageName($imageName) {
		$this->urlImageName = $imageName;
		return $this;
	}

	/**
	 * 图片的真实目录 `/some/directory`
	 * @return string
	 */
	public function getImagePath() {
		$urlImagePath      = $this->getUrlImagePath();
		$urlImagePathArray = explode('/', ltrim($urlImagePath, '/'));
		if (count($urlImagePathArray) < 3) {
			return '';
		}

		//remove route
		array_shift($urlImagePathArray);
		// remove config
		array_shift($urlImagePathArray);
		//remove image name
		array_pop($urlImagePathArray);

		$this->imagePath = '/' . implode('/', $urlImagePathArray);

		return $this->imagePath;

	}

	public function getImageName() {
		$urlImageName = $this->getUrlImageName();
		if (!$urlImageName) {
			return $this->imageName = '';
		}

		$fileNameArray = explode('.', $urlImageName);
		if (!$fileNameArray || count($fileNameArray) < 2) {
			return $this->imageName = '';
		}
		$fileExt = array_pop($fileNameArray);

		$fileNameMain = implode('.', $fileNameArray);

		$fileNameArray = explode(',', $fileNameMain);
		if (!$fileExt || !$fileNameArray || !$fileNameArray[0]) {
			return $this->imageName = '';
		}
		// url with class
		if (is_object($this->config) && isset($this->config->class_separator) && strpos(
				$fileExt,
				$this->config->class_separator
			) !== false
		) {
			$fileExt = substr($fileExt, 0, strpos($fileExt, $this->config->class_separator));

		}
		$fileNameMain = array_shift($fileNameArray);

		return $this->imageName = $fileNameMain . '.' . $fileExt;
	}

	public function getUrlRewritePath() {
		$scriptName = $this->getUrlScriptName();
		if (!$scriptName) {
			return $this->urlRewritePath = '';
		}

		if (false === $this->getUrlRewriteEnabled()) {
			return $this->urlRewritePath = $scriptName;
		}

		$rewritePathArray = explode('/', $scriptName);
		array_pop($rewritePathArray);
		return $this->urlRewritePath = implode('/', $rewritePathArray);
	}


	public function isValid() {
		$host = $this->getHost();
		if (!$host) {
			return false;
		}

		if (!$this->getRoute()) {
			return false;
		}

		if (!$this->getImageName()) {
			return false;
		}

		return true;
	}

	public function toString() {
		$host = $this->getHost();
		if (!$host) {
			return '';
		}
		$port = $this->getPort() ? ':' . $this->getPort() : '';

		$path = $this->getUrlRewritePath();

		if ($urlKey = $this->getRoute()) {
			$path .= "/$urlKey";
		}

		if ($configKey = $this->getConfigKey()) {
			$path .= "/$configKey";
		}

		if ($imagePath = $this->getImagePath()) {
			$path .= $imagePath;
		}

		if ($imageName = $this->getUrlImageName()) {
			$path .= '/' . $imageName;
		}

		$url = $this->getScheme() . '://' . $host . $port . $path;
		$url .= $this->getQuery() ? '?' . $this->getQuery() : '';
		return $url;
	}


	public function getCurrentUrl() {
		$serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
		$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

		if (!$serverName) {
			return '';
		}

		$pageURL = 'http';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$pageURL .= 's';
		}
		$pageURL .= '://';

		if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80') {
			$pageURL .= $serverName . ':' . $_SERVER['SERVER_PORT'] . $requestUri;
		} else {
			$pageURL .= $serverName . $requestUri;
		}
		return $pageURL;
	}

	/**
	 * @return Config\Config
	 */
	public function getConfig() {
		return $this->config;
	}

	public function __construct($url = null, Config\Config $config = null) {
		$this->config = $config;

		$urlString       = $url ? $url : $this->getCurrentUrl();
		$this->urlString = $urlString;

		if ($urlString) {
			$url           = parse_url($urlString);
			$this->scheme  = isset($url['scheme']) ? $url['scheme'] : null;
			$this->host    = isset($url['host']) ? $url['host'] : null;
			$this->port    = isset($url['port']) ? $url['port'] : '';
			$this->query   = isset($url['query']) ? $url['query'] : null;
			$this->urlPath = isset($url['path']) ? $url['path'] : null;
		}
		if ($config == null) {
			return;
		}
		$configKey     = $this->getConfigKey();
		$defaultConfig = $config->current();
		$defaultKey    = $config->key();
		if (isset($config->$configKey)) {
			if ($defaultKey == $configKey) {
				$this->config = $config->$configKey;
			} else {
				$this->config = $defaultConfig->merge($config->$configKey);
			}
		} else {
			throw new Exception\InvalidArgumentException(
				sprintf(
					'No config found by key %s',
					$configKey
				)
			);
		}
	}
}