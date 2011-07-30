<?PHP
/**
 * relationalDbConnections class
 *
 * @package default
 * @author Abhinav  Khanna
 **/
class relationalDbConnections extends dbConnections
{
	
	public function __construct($dbname, $dbhost, $dbuser, $dbpass = null)
	{
		parent::__construct($dbname, $dbhost, $dbuser, $dbpass);
		$this->_conn = $this->open_db_connection();
	}
	
	public function selectWithJoin()
	{
		throw new Exception("Implement Me!");
	}
	
	public function insertIntoTable($tableName1, $keyTable, $foreignKey, $valueForKey, $primaryKey, $arrayOfValues)
	{
		$result = $this->selectFromTable($keyTable, $foreignKey, $valueForKey);
		$num = mysql_num_rows($result);
		if($num == 0 && ($tableName1 != $keyTable))
		{
			$array = array($foreignKey => "$valueForKey");
			parent::insertIntoTable($keyTable, $array);
			$result1 = parent::selectFromTable($keyTable, $foreignKey, $valueForKey);
			$formatted = $this->formatQueryResults($result1, $primaryKey);
			$arrayOfValues[$primaryKey] = $formatted[0];
			parent::insertIntoTable($tableName1, $arrayOfValues);
			return true;
		}
		elseif($num == 0 && ($tableName1 == $keyTable))
		{
			$arrayOfValues[$foreignKey] = $valueForKey;
			parent::insertIntoTable($tableName1, $arrayOfValues);
			return true;
		}
		elseif($tableName1 != $keyTable && $num != 0)
		{
			$formatted = $this->formatQueryResults($result, $primaryKey);
			$arrayOfValues[$primaryKey] = $formatted[0];
			parent::insertIntoTable($tableName1, $arrayOfValues);
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	/**
	 * will update the Relational DB table; if trying to update the table containing the key, give both $tableName1 and $keyTable as the
	 * 			same value.
	 * @param: $tableName1: the table you wish to update
	 * @param: $keyTable: the table containing the cross-table Key
	 * @param: $foreignkey: the foreignKey your using to check;
	 * @param: $valueForKey: the value for the foreignKey;
	 * @param: $primaryKey: the primaryKey of the database (the cross-table Key);
	 * @param: $arrayOfValues: the values being updated;
	 * Postcondition: updates the table if entry already exists;
	 * Postcondition: creates a new entry if entry does not exist;
	 */
	public function updateTable($tableName1, $keyTable, $foreignKey, $valueForKey, $primaryKey, $arrayOfValues, $condition)
	{
		$result = $this->selectFromTable($keyTable, $foreignKey, $valueForKey);
		$num = mysql_num_rows($result);
		if($num == 0)
		{
			$this->insertIntoTable($tableName1, $keyTable, $foreignKey, $valueForKey, $primaryKey, $arrayOfValues);
			return true;
		}
		else
		{
			parent::updateTable($tableName1, $arrayOfValues, $condition);
			return true;
		}
	}
	
} // END class 