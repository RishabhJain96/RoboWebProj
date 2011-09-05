<?php
/**
 * Subclass of relationalDBConnections to allow for easier writing
 */

class dbWriter extends relationalDBConnections
{
	public function __construct($dbname, $dbhost, $dbuser, $dbpass = null)
	{
		parent::__construct($dbname, $dbhost, $dbuser, $dbpass);
		$this->_conn = $this->open_db_connection();
	}
	
	public function writeToDatabse()
	{
		
	}
}

?>