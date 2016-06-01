<?php namespace Imvkmark\L5DbReverse\Console;


use Imvkmark\L5DbReverse\Abstracts\DatabaseCommand;

class MigrationsCommand extends DatabaseCommand {

	/**
	 * The console command name.
	 * @var string
	 */
	protected $name = 'lemon:db-reverse';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Generates a migration file from the tables of the database.';


	/**
	 * Create a new command instance.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function fire() {
		foreach ($this->tables() as $table) {
			if ($table->getName() == 'migrations') continue;

			$columns = $this->schema->listTableColumns($table->getName());

			$argumentWithType = "";

			foreach ($columns as $column) {
				if ($column->getName() == 'id') continue;
				if (!in_array($column->getName(), ['created_at', 'updated_at'])) {
					$argumentWithType .= $column->getName() . ':' . $this->doctrineTypeToGenerator($column->getType()) . ',';
				}
			}

			$argumentWithType = rtrim($argumentWithType, ',');

			$this->callSilent('make:migration:schema', [
				'name'     => "create_{$table->getName()}_table",
				'--schema' => $argumentWithType,
				'--model'  => false,
			]);

			$this->info("Creating migration file for table {$table->getName()}");
		}

	}

	public function doctrineTypeToGenerator($type) {
		$type = strtolower($type);

		switch ($type) {
			case 'integer':
				return 'integer';
			case 'string':
			case 'simplearray':
				return 'string';
			case 'date':
				return 'date';
			case 'datetime':
				return 'datetime';
			case 'time':
				return 'time';
			case 'float':
				return 'float';
			case 'text':
			case 'blob':
				return 'text';
		}
	}

	/**
	 * Get the console command arguments.
	 * @return array
	 */
	protected function getArguments() {
		return [

		];
	}


}
