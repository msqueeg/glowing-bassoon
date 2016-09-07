<?php
/**
* The base controller
* 
*/
class Controller
{
	public $view = null;

	protected $_request = null;

	protected $_action = null;

	protected $_namedParameters = array();
	
	
	public function init()
	{
		$this->view = new View();

		$this->view->settings->action = $this->_action;

		$this->view->settings->controller = strtolower(str_replace('Controller', '', get_class($this)));
	}

	public function beforeFilters()
	{
		# no standard filters
	}

	public function afterFilters()
	{
		# no standard filters
	}


	/**
	 * The main entry point into the controller execution path. The parameter taken is the action to execute.
	 * @author msqueeg <msqueeg@gmail.com>
	 * @version [version]
	 * @date    2016-08-29
	 * @param   string     $action [description]
	 * @return  [type]             [description]
	 */
	public function execute($action = 'index')
	{
		$this->_action = $action;

		$this->init();

		$this->beforeFilters();

		$actionToCall = $action . 'Action';

		$this->$actionToCall();

		$this->afterFilters();

		$this->view->render($this->_getViewScript($action));
	}

	/**
	 * fetches path to view for the given action
	 * @author msqueeg <msqueeg@gmail.com>
	 * @version [version]
	 * @date    2016-08-29
	 * @param   string     $action 
	 * @return  string     the path to the view
	 */
	protected function _getViewScript($action)
	{
		$controller = get_class($this);

		$scriptPath = strtolower(substr($controller, 0 -10) . '/' . $action . '.phtml');

		return $scriptPath;
	}


	protected function _baseUrl()
	{
		return WEB_ROOT;
	}

	public function getRequest()
	{
		if($this->_request == NULL) {
			$this->_request = new Request();
		}

		return $this->_request;
	}

	protected function _getParam($key, $default = null)
	{
		if (isset($this->_namedParameters[$key])) {
			return $this->_namedParameters[$key];
		}

		return $this->getRequest()->getParam($key, $default);
	}

	protected function _getAllParams()
	{
		return array_merge($this->getRequest()->getAllParams(), $this->_namedParameters);
	}

	protected function addNamedParameter($key, $value)
	{
		$this->_namedParameters[$key] = $value;
	}

}