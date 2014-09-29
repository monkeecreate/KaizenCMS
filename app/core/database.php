<?php
class Stratum {
	private $dbh; // Database handle
	private $funcCall; // Last method call
	private $lastResult;
	private $lastSQL; // Last SQL ran
	private $result;
	private $connected;
	private $selectedDatabase;
	private $error;

	public $fetchMode = "assoc";
	public $debug = true;

	public function __construct($dbuser = "", $dbpassword = "", $dbname = "", $dbhost = "") {
		if(!empty($dbuser) && !empty($dbname) && !empty($dbhost)) {
			$this->connect($dbuser, $dbpassword, $dbname, $dbhost, debug_backtrace());
		}
	}

	public function connect($dbuser, $dbpassword, $dbname, $dbhost, $aBacktrace = array()) {
		$dsn = 'mysql:dbname='.$dbname.';host='.$dbhost;

		$this->dbh = new PDO($dsn,$dbuser,$dbpassword);

		if(empty($aBacktrace)) {
			$aBacktrace = debug_backtrace();
		}

		if(!$this->dbh) {
			return $this->_throwError("Error establishing a database connection!", $aBacktrace);
		} else {
			return true;
		}
	}

	//Basic Query	- see docs for more detail
	public function query($query, $data = null) {
		// Log how the function was called
		$this->funcCall = "\$db->query(\"".$query."\")";

		// Kill this
		$this->lastResult = null;

		// Keep track of the last query for debug..
		$this->lastSQL = $query;

		// Perform the query
		$sth = $this->dbh->prepare($query);
		$sth->execute($data);
		if($sth->errorCode() == '00000') {
			$oResult = $sth->fetchAll(PDO::FETCH_OBJ);
			if(count($oResult) > 0) {
				// Store Query Results
				foreach($oResult as $row) {
					// Store relults as an objects within main array
					$this->lastResult[] = $row;
				}
			}

			$rows = $sth->rowCount();
			unset($oResult);
			unset($sth);

			return $rows;
		} else {
			// If there is an error then take note of it..
			$this->_throwError(null, debug_backtrace());
		}
	}

	//Get one variable from the DB
	public function getOne($query = null, $row = 0, $column = 0) {
		// Log how the function was called
		$this->funcCall = "\$db->getOne(\"".$query."\", ".$row.", ".$column.")";

		// If there is a query then perform it if not then use cached results..
		if($query) {
			$this->query($query);
		}

		if(!is_numeric($row)) {
			$row = 0;
		}

		if(!is_numeric($column)) {
			$column = 0;
		}

		// Extract var out of cached results based x,y vals
		if($this->lastResult[$row]) {
			$values = array_values(get_object_vars($this->lastResult[$row]));
		}

		// If there is a value return it else return null
		return $values[$column]?$values[$column]:null;
	}

	//Get one row from the DB
	public function getRow($query = null, $fetchMode = null, $y = 0) {
		// Log how the function was called
		$this->funcCall = "\$db->get_row(\"$query\",$y,$fetchMode)";

		// If there is a query then perform it if not then use cached results..
		if($query) {
			$this->query($query);
		}

		if(!is_numeric($y)) {
			$y = 0;
		}

		if(empty($fetchMode)) {
			$fetchMode = $this->fetchMode;
		}

		switch($fetchMode) {
			case "object":
				// If the fetchMode is an object then return object using the row offset..
				return $this->lastResult[$y]?$this->lastResult[$y]:array();
				break;
			case "assoc":
				// If the fetchMode is an associative array then return row as such..
				return $this->lastResult[$y]?get_object_vars($this->lastResult[$y]):array();
				break;
			default: //ordered
				// If the fetchMode is an numerical array then return row as such..
				return $this->lastResult[$y]?array_values(get_object_vars($this->lastResult[$y])):array();
		}
	}

	// Function to get 1 column from the cached result set based in X index
	public function getCol($query = null, $x = 0) {
		// If there is a query then perform it if not then use cached results..
		if($query) {
			$this->query($query);
		}

		// Extract the column values
		for($i=0; $i < count($this->lastResult); $i++) {
			$new_array[$i] = $this->getOne(null,$x,$i);
		}

		return $new_array;
	}

	// Return the the query as a result set - see docs for more details
	public function getAll($query = null, $fetchMode = null) {
		// Log how the function was called
		$this->funcCall = "\$db->get_results(\"$query\", $fetchMode)";

		// If there is a query then perform it if not then use cached results..
		if ($query) {
			$this->query($query);
		}

		if(empty($fetchMode)) {
			$fetchMode = $this->fetchMode;
		}

		// Send back array of objects. Each row is an object
		if($fetchMode == "object") {
			return $this->lastResult;
		} elseif($fetchMode == "assoc" || $fetchMode == "ordered") {
			if($this->lastResult) {
				$i=0;
				$new_array = array();
				foreach($this->lastResult as $row) {
					$new_array[$i] = get_object_vars($row);

					if ($fetchMode == "ordered") {
						$new_array[$i] = array_values($new_array[$i]);
					}

					$i++;
				}

				return $new_array;
			} else {
				return array();
			}
		}
	}

	public function insert($table, $data) {
		$sSQL = 'INSERT INTO `'.$table.'`';

		$aInsertSet = array();
		$aInsertData = array();
		foreach($data as $sKey=>$sValue) {
			$aInsertSet[] = '`'.$sKey.'` = ?';
			$aInsertData[] = $sValue;
		}

		$sSQL .= ' SET '.implode(', ', $aInsertSet);

		$this->query($sSQL, $aInsertData);

		return $this->lastInsertID();
	}

	public function update($table, $data, $where) {
		$sSQL = 'UPDATE `'.$table.'`';

		$aUpdateSet = array();
		$aUpdateData = array();
		foreach($data as $sKey=>$sValue) {
			if($sKey != '.Where') {
				$aUpdateSet[] = '`'.$sKey.'` = ?';
			}

			$aUpdateData[] = $sValue;
		}

		$sSQL .= ' SET '.implode(', ', $aUpdateSet);
		$sSQL .= ' WHERE '.$where;

		$this->query($sSQL, $aUpdateData);

		return true;
	}

	public function delete($table, $data, $where) {
		$sSQL = 'DELETE FROM `'.$table.'` WHERE '.$where;

		$this->query($sSQL, $data);

		return true;
	}

	// Retrieve last AUTO_INCREMENT id created by this connection
	public function lastInsertID() {
		return $this->dbh->lastInsertId();
	}

	// Retrieve number of rows affected by last query
	public function affectedRows() {
		// return $this->dbh->affected_rows;
	}

	// Escape given string to place into SQL
	public function escape($string) {
		return $this->dbh->quote($string);
	}

	// Removes all stored info of previous query
	public function free() {
		unset($this->lastResult);
		unset($this->lastSQL);
		unset($this->result);
		unset($this->error);
	}

	// Close connection to database
	public function disconnect() {
		$this->free();
		unset($this->dbh);
	}

	// Get error data
	public function getError() {
		return $this->error;
	}

	//Print SQL/DB error.
	private function _throwError($sError = null, $aBacktrace = array()) {
		if(empty($sError)) {
			$sError = $this->dbh->errorInfo[2];
		}

		if(empty($aBacktrace)) {
			$aBacktrace = debug_backtrace();
		}

		$this->error = array("error" => $sError, "backtrace" => $aBacktrace);

		if($this->debug == true) {
			// Display error and stop processing
			echo "<br>\n";
			echo "<b>Database Error</b>: ".$sError." in <b>".$aBacktrace[0]["file"]."</b> on line <b>".$aBacktrace[0]["line"]."</b><br>\n";
			$this->disconnect();
			die;
		} else {
			return false;
		}
	}
}
