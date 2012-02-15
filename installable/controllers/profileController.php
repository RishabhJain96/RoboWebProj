<?php
/**
 * profileController Class
 * 
 * This class holds all the server-side methods related to processing profile data.
 * This class is currently empty but don't delete it because some of the client-side code instantiates this class, even though the needed methods are in the roboSISAPI class. It work because this is a subclass of roboSISAPI.
 */

class profileController extends roboSISAPI
{
	// instance variables
	protected $_dbConnection;
	
	public function __construct()
	{
		parent::__construct();
	}
}

?>