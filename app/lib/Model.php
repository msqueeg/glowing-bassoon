<?php
/**
* A base model for handling the database connection
*/	

namespace Msqueeg\Lib;

class Model
{
	protected $_dbh = null;
	protected $_table = "";
	
	function __construct()
	{
		$settings = parse_ini_file(APP_PATH . '/config/settings.ini', true);

		//connection using Medoo library
		$this->_dbh = new \medoo([
			'database_type' => $settings['db']['type'],
			'database_name' => $settings['db']['name'],
			'server' => $settings['db']['host'],
			'username' => $settings['db']['username'],
			'password' => $settings['db']['password']
			]);

		$this->init();
	}

	public function init()
	{
		# code...
	}

	/**
	 * set the database table the model is using
	 * @author msqueeg <msqueeg@gmail.com>
	 * @version [version]
	 * @date    2016-08-29
	 * @param   [type]     $table [description]
	 */
	protected function _setTable($table)
	{
		$this->_table = $table;
	}

	public function getTable()
	{
		return $this->_table;
	}

	/**
	 * return single record for database
	 * @author msqueeg <msqueeg@gmail.com>
	 * @version [version]
	 * @date    2016-08-29
	 * @param   [type]     $id [description]
	 * @return  [type]         [description]
	 */
	public function getOne($id)
	{
		return $this->_dbh->get($this->_table,"*", [ 'id' => $id ] );
	}

	public function selectRecords()
	{
		return $this->_dbh->select($this->_table,"*");
	}

	/**
	 * saves the current data to the database. If a key named "id"
	 * is given, an update will be issued.
	 * @author msqueeg <msqueeg@gmail.com>
	 * @version [version]
	 * @date    2016-08-29
	 * @param   array      $data [description]
	 * @return  [type]           [description]
	 */
	public function saveRecord($data = array())
	{
		if(array_key_exists('id', $data)) {

			$id = $data['id'];

            unset($data['id']);

			$lastRecord = $this->_dbh->update($this->_table,$data,[ 'id' => $id]);

		} else {
			$lastRecord = $this->_dbh->insert($this->_table,$data);
		}

		return $lastRecord;
	}

	public function delete($id)
	{
		$result = $this->_dbh->delete($this->_table,['id' => $id]);
		return $result;
	}
}