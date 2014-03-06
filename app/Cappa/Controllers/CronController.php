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
		CappaMan::processDividendQueue();
		return "Ran cappa process...";
	}

}
