<?php namespace Cappa\GenePool\Controller;

use Illuminate\Routing\Controller as LaravelController;
use Route;

abstract class Root extends LaravelController {


	// Convenience function to replace Route::controller()
	//  this function should replace the functionality to add assumed route names
	public static function register($routePattern)
	{
		
		$routable = Route::getInspector()->getRoutable($this);
// Make an assumption for route names

		Route::controller($routePattern, $this, $routeNames);

	}



/*
	protected $beforeFilters = array();

	protected $afterFilters = array();

	protected static $filterer;

	protected $layout;

	public function beforeFilter($filter, array $options = array())
	{
		$this->beforeFilters[] = $this->parseFilter($filter, $options);
	}

	public function afterFilter($filter, array $options = array())
	{
		$this->afterFilters[] = $this->parseFilter($filter, $options);
	}

	protected function parseFilter($filter, array $options)
	{
		$parameters = array();

		$original = $filter;

		if ($filter instanceof Closure)
		{
			$filter = $this->registerClosureFilter($filter);
		}
		elseif ($this->isInstanceFilter($filter))
		{
			$filter = $this->registerInstanceFilter($filter);
		}
		else
		{
			list($filter, $parameters) = Route::parseFilter($filter);
		}

		return compact('original', 'filter', 'parameters', 'options');
	}

	protected function registerClosureFilter(Closure $filter)
	{
		$this->getFilterer()->filter($name = spl_object_hash($filter), $filter);

		return $name;
	}

	protected function registerInstanceFilter($filter)
	{
		$this->getFilterer()->filter($filter, array($this, substr($filter, 1)));

		return $filter;
	}

	protected function isInstanceFilter($filter)
	{
		if (is_string($filter) && starts_with($filter, '@'))
		{
			if (method_exists($this, substr($filter, 1))) return true;

			throw new \InvalidArgumentException("Filter method [$filter] does not exist.");
		}

		return false;
	}

	public function getBeforeFilters()
	{
		return $this->beforeFilters;
	}

	public function getAfterFilters()
	{
		return $this->afterFilters;
	}

	public static function getFilterer()
	{
		return static::$filterer;
	}

	public static function setFilterer(RouteFiltererInterface $filterer)
	{
		static::$filterer = $filterer;
	}

	protected function setupLayout() {}

	public function callAction($method, $parameters)
	{
		$this->setupLayout();

		$response = call_user_func_array(array($this, $method), $parameters);

		// If no response is returned from the controller action and a layout is being
		// used we will assume we want to just return the layout view as any nested
		// views were probably bound on this view during this controller actions.
		if (is_null($response) and ! is_null($this->layout))
		{
			$response = $this->layout;
		}

		return $response;
	}

	public function missingMethod($parameters = array())
	{
		throw new NotFoundHttpException("Controller method not found.");
	}

	public function __call($method, $parameters)
	{
		throw new \BadMethodCallException("Method [$method] does not exist.");
	}
*/

}
