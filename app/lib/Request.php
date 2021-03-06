<?php
/**
* 
*/

namespace Msqueeg\Lib;

class Request
{
	/**
	 * Test if the current request is a POST request
	 * @author msqueeg <msqueeg@gmail.com>
	 * @version [version]
	 * @date    2016-08-29
	 * @return  boolean    [description]
	 */
	public function isPost()
	{
		return ($_SERVER['REQUEST_METHOD'] == 'POST' ? true : false);
	}

	protected function _isGet()
	{
		return ($_SERVER['REQUEST_METHOD'] == 'GET' ? true : false);
	}

	public function getParam($key, $default = null)
	{
		if($this->isPost()) {
			if(isset($_POST[$key])) {
				return $_POST[$key];
			}
		}

		else if ($this->_isGet()) {
			if(isset($_GET[$key])) {
				return $_GET[$key];
			}
		}

		return $default;
	}

	public function getAllParams()
	{
		if($this->isPost()) {
			return $_POST;
		}
		else if ($this->_isGet()) {
			return $_GET;
		}
	}
}