<?php
/**
* Controller for Index route
*/

namespace Msqueeg\Controllers;

class IndexController extends ApplicationController
{
	
	public function indexAction()
	{
		$this->view->message = 'Hello from index::action';
	}
}