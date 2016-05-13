<?php namespace App\Console\Commands\Lemon;

use Illuminate\Console\Command;

class Fe extends Command {

	/**
	 * 前端部署.
	 * @var string
	 */
	protected $signature = 'lemon:fe';

	/**
	 * 描述
	 * @var string
	 */
	protected $description = 'Deploy lemon front files.';


	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle() {
		$frontJs = 'assets/js/global.js';
		$disk    = \Storage::disk('public');
		$js      = js_global();
		if ($disk->put($frontJs, $js)) {
			$this->info('File ' . $frontJs . ' regenerated!');
		} else {
			$this->error('File ' . $frontJs . ' not Writable!');
		}
	}
}
