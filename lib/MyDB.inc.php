<?php
/**
* User Directory
*   Copyright(c) 2008 Theodore R. Smith <theodore@phpexperts.pro>
* 
* The following code is licensed under a modified BSD License.
* All of the terms and conditions of the BSD License apply with one
* exception:
*
* 1. Every one who has not been a registered student of the "PHPExperts
*    From Beginner To Pro" course (http://www.phpexperts.pro/) is forbidden
*    from modifing this code or using in an another project, either as a
*    deritvative work or stand-alone.
*
* BSD License: http://www.opensource.org/licenses/bsd-license.php
**/

class MyDBException extends Exception 
{
	const BAD_SQL = 101;
	const CANT_LOAD_CONFIG_FILE = 102;
	const BAD_CONFIG_FILE = 103;
	const NO_DB_ENGINE = 104;

	public function __construct($message = null, $code = 0)
	{
		return parent::__construct($message, $code);
	}
}

class MyDBConfigStruct
{
	public $hostname;
	public $port = 3306;
	public $username;
	public $password;
	public $database;

	/**
	 * Converts a stdClass object to MyDBConfigStruct object
	 *
	 * @param stdClass $config
	 * @return MyDBConfigStruct
	 */
	public static function fromStd(stdClass $config)
	{
		$dbConfig = new MyDBConfigStruct;
		$params = get_object_vars($config);

		unset($config);

		foreach ($params as $param => $value)
		{
			if (property_exists($dbConfig, $param))
			{
				$dbConfig->$param = $value;
			}
		}
		
		return $dbConfig;
	}
}

interface MyDBI
{
	// Basic SQL functions
	public function query($sql, array $params = null);
	public function fetchArray();
	public function fetchObject($objectType = 'stdClass');
	
	// Transactional SQL functions
	public function beginTransaction();
	public function commit();
	public function rollback();
	
	// Write SQL functions
	public function lastInsertId();
}

// Factory pattern
class MyDB
{
	// @codeCoverageIgnoreStart
	private function __construct() { }
	// @codeCoverageIgnoreStop

	public static function loadDB(stdClass $config = null)
	{
		static $_config;

		// Auto-return the last config
		if (is_null($config) && !is_null($_config))
		{
			$config = $_config;
		}
		
		// Never store passes in plaintext!!
		if (is_null($config))
		{
			if (!file_exists('database.config'))
			{
				throw new MyDBException('Couldn\'t find database.config.', MyDBException::CANT_LOAD_CONFIG_FILE);
			}

			$data = file_get_contents('database.config');
			
			if ($data === false)
			{
				throw new MyDBException('Couldn\'t load database.config.', MyDBException::CANT_LOAD_CONFIG_FILE);
			}

			$data = base64_decode($data);
			$config = json_decode($data);

			if ($config === false || is_null($config))
			{
				throw new MyDBException('Couldn\'t successfully parse database.config.', MyDBException::BAD_CONFIG_FILE);
			}
		}

		$_config = $config;

		if (!isset($config->engine))
		{
			throw new MyDBException('database.config: No database engine specified.', MyDBException::NO_DB_ENGINE);
		}

		$useReplication = isset($config->useReplication) ? $config->useReplication : false;
        $className = 'MyDB_Engine_' . $config->engine;
		if ($useReplication)
		{
			$className = 'MyReplicated' . $config->engine;

			return new $className($config);
		}

		if (!class_exists($className))
		{
			throw new MyDBException('database.config: Could not find the DB Engine ' . $className . '.', MyDBException::NO_DB_ENGINE);
		}

		$dbConfig = MyDBConfigStruct::fromStd($_config);

		return new $className($dbConfig);
	}

	public static function printQuery($query, $params)
	{
		$keys = array();

		# build a regular expression for each parameter
		foreach ($params as $key => &$value) {
			if (is_string($key)) {
				$keys[] = '/:'.$key.'/';
			} else {
				$keys[] = '/[?]/';
			}

            if (is_string($value)) {
                $value = "'$value'";
            }
		}

		$query = preg_replace($keys, $params, $query, 1, $count);
        if (substr($query, -1) != ';') { $query .= ';'; }

		return $query;
	}
}

abstract class MyReplicatedDB implements MyDBI 
{
	/**
	 * DB handle that does all the reading
	 * @var MyDBI
	 * @access protected
	 */
	protected $dbReader;

	/**
	 * DB handle that does all the writing
	 * @var MyDBI
	 * @access protected
	 */
	protected $dbWriter;

	/**
	 * @param string $sql
	 * @param array $params
	 * @return PDOStatement
	 */
	public function query($sql, array $params = null)
	{
		$operation = strtoupper(substr($sql, 0, 6));

		// Detect read operation
		if ($operation == 'SELECT')
		{
			return $this->dbReader->query($sql, $params);
		}
		else
		{
			return $this->dbWriter->query($sql, $params);
		}
	}

	public function fetchArray()
	{
		return $this->dbReader->fetchArray();
	}

	public function fetchObject($objectType = 'stdClass')
	{
		return $this->dbReader->fetchObject($objectType);
	}	

	public function beginTransaction()
	{
		return $this->dbWriter->beginTransaction();
	}

	public function commit()
	{
		return $this->dbWriter->commit();
	}

	public function rollback()
	{
		return $this->dbWriter->rollback();
	}
	
	public function lastInsertId()
	{
		return $this->dbWriter->lastInsertId();
	}
	
}

/**
 * MyDB interface for PDO 
 */
class MyPDO implements MyDBI
{
	/** @var PDO **/
	private $pdo;

	/** @var PDOStatement **/
	private $stmt;

	public function __construct(MyDBConfigStruct $config)
	{
		$dsn = sprintf('mysql:dbname=%s;host=%s;port=%d;', $config->database, $config->hostname, $config->port);
		$this->pdo = new PDO($dsn, $config->username, $config->password);	
	}

	// Accessors
	public function getPDO()
	{
		return $this->pdo;
	}

	/**
	 * Queries the database
	 * 
	 * @param string $sql
	 * @param array $params
	 * @return PDOStatement
	 */
	public function query($sql, array $params = null)
	{
		$stmt = $this->pdo->prepare($sql);
		
		if (!$stmt->execute($params))
		{
			// Do NOT show SQL errors (security risk)
			$errorInfo = $stmt->errorInfo();
			error_log(sprintf('SQL error: (%s) - %s',
			                  $errorInfo[1],
			                  $errorInfo[2]));
			throw new MyDBException('Caught an SQL error...see error log for more details.', MyDBException::BAD_SQL);
		}
		
		$this->stmt = $stmt;
 
		return $stmt;
	}

	public function fetchArray()
	{
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function fetchObject($objectType = 'stdClass')
	{
		return $this->stmt->fetchObject($objectType);
	}

	public function beginTransaction()
	{
		return $this->pdo->beginTransaction();
	}

	public function commit()
	{
		return $this->pdo->commit();
	}

	public function rollback()
	{
		return $this->pdo->rollBack();
	}

	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
}

class MyDB_Engine_PDO extends MyPDO
{
}

class MyReplicatedPDO extends MyReplicatedDB implements MyDBI 
{
	public function __construct($config)
	{
		if (!isset($config->readDB))
		{
			throw new MyDBException('Replication support enabled, but no read database specified', MyDBException::BAD_CONFIG_FILE);
		}
		
		$readDB = MyDBConfigStruct::fromStd($config->readDB);

		$this->dbReader = new MyPDO($readDB);
		
		if (isset($config->writeDB))
		{
			$writeDB = MyDBConfigStruct::fromStd($config->writeDB);
			
			$this->dbWriter = new MyPDO($writeDB);
		}
	}
}

/**
 * Enter description here...
 *
 * @param stdClass $config
 * @return MyDBI
 */
function getDBHandler(stdClass $config = null)
{
	static $myDB = null;
	
	if (!is_null($myDB) && is_null($config))
	{
		return $myDB;
	}

	$myDB = MyDB::loadDB($config);

	return $myDB;
}

function queryDB($sql, array $params = null)
{
	$myDB = getDBHandler();
	
	return $myDB->query($sql, $params);
}
