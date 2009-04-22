<?php

require_once 'PHPUnit/Framework/TestCase.php';

require_once 'lib/MyDatabase.inc.php';

/**
 * queryDB() test case.
 */
class MyDatabaseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @return MyDBConfigStruct
	 */
	public static function getPDOConfig()
	{
		$config = new stdClass;
		$config->engine = 'PDO';
		$config->hostname = 'localhost';
		$config->username = 'ud_tester';
		$config->password = 'PHxhu6u6-)';
		$config->database = 'TEST_user_directory';		
		
		return $config;
	}

	public static function getReplicatedPDOConfig()
	{
		$config = new stdClass;
		$config->engine = 'PDO';
		
		$readDB = new stdClass;
		$readDB->hostname = 'localhost';
		$readDB->username = 'ud_testreader';
		$readDB->password = 'PHxhu6u6-)r';
		$readDB->database = 'TEST_user_directory';		

		$writeDB = new stdClass;
		$writeDB->hostname = 'localhost';
		$writeDB->username = 'ud_testwriter';
		$writeDB->password = 'PHxhu6u6-)w';
		$writeDB->database = 'TEST_user_directory';

		$config->useReplication = true;
		$config->readDB = $readDB;
		$config->writeDB = $writeDB;
		
		return $config;
	}

	/**
	 * Tests getDBHandler()
	 */
	public function testGetDBHandler()
	{
		// Test with a missing config file
		try
		{
			getDBHandler();
		}
		catch (MyDBException $e)
		{
			$this->assertEquals(MyDBException::CANT_LOAD_CONFIG_FILE, $e->getCode());
		}

		// Test create MyDB from scratch
		// (change current directory to be able to find config path)
		chdir(dirname(__FILE__) . '/..');
		$pdo = getDBHandler();
		$this->assertType('MyDBI', $pdo, 'PDO object is not of type PDO');;

		// Test with custom config
		$config = self::getPDOConfig();
		$new_pdo = getDBHandler($config);
		$this->assertType('MyDBI', $new_pdo, 'PDO object is not of type PDO');;
	}

	/**
	 * Tests queryDB()
	 */
	public function testQueryDB()
	{
		$config = self::getPDOConfig();
	     getDBHandler($config);

        	// Test insert
		$username = uniqid();
		queryDB('INSERT INTO users (username, password) VALUES (\'' . $username . '\', \'' . uniqid() . '\')');

		// Test select w/o parameters
		$stmt = queryDB('SELECT * FROM Users WHERE username=\'' . $username . '\'');
		$userInfo = $stmt->fetchObject();
		$this->assertNotNull($userInfo, 'Unsuccessful query');
		$this->assertSame($username, $userInfo->username);
	
		// Test select w/ parameters
		$stmt = queryDB('SELECT * FROM Users WHERE username=?', array($username));
		$userInfo = $stmt->fetchObject();
		$this->assertNotNull($userInfo, 'Unsuccessful query');
		$this->assertSame($username, $userInfo->username);
		
		// Test select w/ malformed SQL
		$this->setExpectedException('MyDBException');
		$stmt = queryDB('SELECT * FROM usersasdf WHERE username=?', array($username));
	}	
}

