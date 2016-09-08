<?php
/**
* Data model for Accounts table
*/

namespace Msqueeg\Models;

class Accounts extends Base
{
	
	public function init()
	{
		$this->_setTable('accounts');
	}
}