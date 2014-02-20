<?php namespace Cappa\Facades;

use Illuminate\Support\Facades;

class Manager extends \Illuminate\Support\Facades\Facade {

	protected static function getFacadeAccessor() { return 'cappa.manager'; }

}
