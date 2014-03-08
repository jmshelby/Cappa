<?php namespace Cappa\Controllers;

use \App;
use \CappaMan;

class CronController extends \Cappa\GenePool\Controller\Root {

	public $service;

	public function __construct()
	{
		$this->service = App::make('cappa.service.frontend');
	}

	public function getProcessQueue()
	{
$start = microtime(true);
        CappaMan::processDividendQueue();
$end = microtime(true);
$total = $end - $start;
\Log::info("Ran cappa process in $total seconds.");
return "Ran cappa process in $total seconds.";
	}

}
