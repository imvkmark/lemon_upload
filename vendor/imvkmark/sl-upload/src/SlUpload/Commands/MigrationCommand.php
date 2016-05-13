<?php namespace Imvkmark\SlUpload\Commands;

/**
 * This file is part of sour lemon upload,
 * @license MIT
 * @package Imvkmark\SlUpload
 */

use Illuminate\Console\Command;

class MigrationCommand extends Command {

	/**
	 * The console command name.
	 * @var string
	 */
	protected $name = 'lemon:upload_migration';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Creates a migration following the sour lemon upload specifications.';

	/**
	 * Execute the console command.
	 * @return void
	 */
	public function fire() {
		$this->laravel->view->addNamespace('sl-upload', substr(__DIR__, 0, -8) . 'views');


		$imageKeyTable    = \Config::get('sl-upload.image_key_table');
		$imageUploadTable = \Config::get('sl-upload.image_upload_table');

		$this->line('');
		$this->info("Tables: $imageKeyTable, $imageUploadTable");

		$message = "A migration that creates '$imageKeyTable', '$imageUploadTable' tables will be created in database/migrations directory";

		$this->comment($message);
		$this->line('');

		if ($this->confirm("Proceed with the migration creation? [Yes|no]", "Yes")) {

			$this->line('');

			$this->info("Creating migration...");
			if ($this->createMigration($imageKeyTable, $imageUploadTable)) {

				$this->info("Migration successfully created!");
			} else {
				$this->error(
					"Couldn't create migration.\n Check the write permissions" .
					" within the database/migrations directory."
				);
			}

			$this->line('');

		}
	}

	/**
	 * Create the migration.
	 * @param string $name
	 * @return bool
	 */
	protected function createMigration($imageKeyTable, $imageUploadTable) {
		$migrationFile = base_path("/database/migrations") . "/" . date('Y_m_d_His') . "_sl-upload_setup_tables.php";

		$image_key    = $imageKeyTable;
		$image_upload = $imageUploadTable;
		$data         = compact('image_key', 'image_upload');

		$output = $this->laravel->view->make('sl-upload::generators.migration')->with($data)->render();

		if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {
			fwrite($fs, $output);
			fclose($fs);
			return true;
		}

		return false;
	}
}
