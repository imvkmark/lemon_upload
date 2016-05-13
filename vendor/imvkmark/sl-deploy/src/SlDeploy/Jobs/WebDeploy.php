<?php namespace Imvkmark\SlDeploy\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class WebDeploy extends Job implements SelfHandling, ShouldQueue {

	private $shellPath;

	/**
	 * Create a new job instance.
	 */
	public function __construct() {
		$this->shellPath = dirname(dirname(__DIR__));
	}

	/**
	 * Execute the job.
	 * @return void
	 */
	public function handle() {
		$shell   = "/bin/bash " . $this->shellPath . '/resources/shell/deploy.sh' . ' ' . base_path();
		$process = new Process($shell);
		$process->start();
		$process->wait(function ($type, $buffer) {
			if (Process::ERR === $type) {
				echo 'ERR > ' . $buffer;
			} else {
				echo 'OUT > ' . $buffer;
			}
		});
	}
}
