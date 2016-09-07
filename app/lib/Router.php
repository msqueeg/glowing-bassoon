<?php
/**
* basic routing
*/

namespace Msqueeg\Lib;

class Router
{
	
	public function execute($routes)
	{
		try {
			$controller = null;
			$action = null;

			$routeFound = $this->_getSimpleRoute($routes, $controller, $action);

			if(!$routeFound) {

				$routeFound = $this->_getParameterRoute($routes, $controller, $action);
			}

			if(!$routeFound || $controller == null || $action == null) {
				throw new Exception('no route added for ' . $_SERVER['REQUEST_URI']);
			}

			else {
				$controller->execute($action);
			}
		}

		catch(Exception $exception) {

			$controller = new ErrorController();
			$controller->setException($exception);
			$controller->execute('error');

		}
	}

	public function hasParameters($route)
	{
		return preg_match('/(\/:[a-z]+/', $route);
	}

	protected function _getUri()
	{
		$uri = explode('?', $_SERVER['REQUEST_URI']);
		$uri = $uri[0];
		$uri = substr($uri, strlen(WEB_PATH));

		return $uri;
	}

	protected function _getSimpleRoute($routes, &$controller, &$action)
	{
		$uri = $this->_getUri();

		//if the route isn't defined, try to add a trailing slash
		if (isset($routes[$uri])) {
			$routeFound = $routes[$uri];
		}
		else if (isset($routes[$uri . '/'])) {
			$routeFound = $routes[$uri . '/'];
		}
		else {
			$uri = substr($uri, 0 ,-1);

			$routeFound = isset($routes[$uri]) ? $routes[$uri] : false ;
		}

		if($routeFound) {
			list($name, $action) = explode('#', $routeFound);

			$controller = $this->_initializeController($name);

			return true;
		}

		return false;
	}

	protected function _getParameterRoute($routes, &$controller, &$action)
	{
		//fetch the URI
		$uri = $this->_getUri();

		//testing routes with parameters
		foreach ($routes as $route => $path) {
			if($this->hasParameters($route)) {
				$uriParts = explode('/:', $route);

				$pattern = '/^';

				if($uriParts[0] == '') {
					$pattern .= '\\/';
				}
				else {
					$pattern .= str_replace('/', '\\/', $uriParts[0]);
				}

				foreach(range(1, count($uriParts)-1) as $index) {
					$pattern .= '\/([a-zA-Z0-9]+)';
				}

				$pattern .= '[\/]{0,1}$/';

				$namedParameters = array();
				$match = preg_match($pattern, $uri, $namedParameters);

				//if the route matches
				if($match) {

					list($name, $action) = explode('#',$path);

					//initialize the controller
					$controller = $this->_initializeController($name);
					
					//adds the named parameters to the controller
					foreach (range(1,count($namedParameters)-1) as $index) {
						$controller->addNamedParameter( 
												$uriParts[$index],
												$namedParameters[$index]
												);
					}

					return true;
				}

			}
		}		
		return false;
	}

	/**
	 * initializes the given controller
	 * @author msqueeg <msqueeg@gmail.com>
	 * @version [version]
	 * @date    2016-08-29
	 * @param   [type]     $name [description]
	 * @return  mixed null if error, else a controller
	 */
	protected function _initializeController($name)
	{
		$controller = 'Msqueeg\\Controllers\\' . ucfirst($name) . 'Controller';
		return new $controller();
	}
}