<?php namespace Imvkmark\L5DbReverse\Abstracts;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Illuminate\Console\Command;

use Symfony\Component\Console\Input\InputOption;

abstract class DatabaseCommand extends Command {


	/** @type \Doctrine\DBAL\Schema\AbstractSchemaManager */
	protected $schema;

	/** @type string 数据库前缀 */
	protected $prefix;

	public function __construct() {
		$config = new Configuration();

		$connectionParams = [
			'dbname'   => config('database.connections.mysql.database'),
			'user'     => config('database.connections.mysql.username'),
			'password' => config('database.connections.mysql.password'),
			'host'     => config('database.connections.mysql.host'),
			'driver'   => 'pdo_mysql',
		];

		$conn = DriverManager::getConnection($connectionParams, $config);

		$conn->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

		$this->schema = $conn->getSchemaManager();
		$this->prefix = config('database.connections.mysql.prefix');
		parent::__construct();
	}

	/**
	 * @return \Doctrine\DBAL\Schema\AbstractAsset[]
	 */
	protected function tables() {
		if ($this->passedOnlyAndExcept()) {
			$this->error('You can`t set the only and the except param at the same time.');
			exit;
		}

		$tables = $this->schema->listTables();

		$tables_to_return = [];

		if ($this->passedOnly()) {
			foreach ($tables as $table) {
				if (in_array($table->getName(), $this->commaParse($this->option('only')))) {
					$tables_to_return[] = $table;
				}
			}
		}

		if ($this->passedExcept()) {
			foreach ($tables as $table) {
				if (!in_array($table->getName(), $this->commaParse($this->option('only')))) {
					$tables_to_return[] = $table;
				}
			}
		}

		if (!$this->passedExcept() && !$this->passedOnly()) {
			foreach ($tables as $table) {
				$tables_to_return[] = $table;
			}
		}

		return $tables_to_return;
	}

	/**
	 * Get the console command options.
	 * @return array
	 */
	protected function getOptions() {
		return [
			['only', null, InputOption::VALUE_OPTIONAL, 'The tables to make the action.', null],
			['except', null, InputOption::VALUE_OPTIONAL, 'The tables to not make the action.', null],
			['filename', null, InputOption::VALUE_OPTIONAL, 'The name of the file.', null],
		];
	}

	/**
	 * @return bool
	 */
	protected function passedOnlyAndExcept() {
		return ($this->passedOnly() && $this->passedExcept());
	}

	/**
	 * @return bool
	 */
	protected function passedOnly() {
		return !is_null($this->option('only'));
	}

	/**
	 * @return bool
	 */
	protected function passedExcept() {
		return !is_null($this->option('except'));
	}

	/**
	 * 解析 `,` 分隔
	 * @param $string
	 * @return array
	 */
	protected function commaParse($string) {
		$string = str_replace(' ', '', $string);
		$arr    = explode(',', $string);
		foreach ($arr as $k => $tb) {
			$arr[$k] = $this->tableName($tb);
		}
		return $arr;
	}

	/**
	 * 获取完整的表名称
	 * @param $name
	 * @return string
	 */
	protected function tableName($name) {
		if (substr($name, 0, strlen($this->prefix)) == $this->prefix) {
			return $name;
		} else {
			return $this->prefix . $name;
		}
	}
}
