<?php
/**
 * checkinController Class
 * 
 * Description
 */

class checkinController extends roboSISAPI
{
	// constants
	const MAX_CHECKINS_PER_DAY = 1; // changed this to change the max number of check ins
	
	// instance variables
	protected $_dbConnection;
	
	public function __construct()
	{
		parent::__construct();
	}
	
}

?>