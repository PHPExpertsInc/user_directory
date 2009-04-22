<?php

require_once 'PHPUnit/Framework/TestCase.php';

require_once 'lib/MyDatabase.inc.php';

/**
 * queryDB() test case.
 */
class DBFunctionsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests getDBHandler()
	 */
	public function testGetDBHandler()
	{
		// Test create PDO from scratch
		// (change current directory to be able to find config path)
		chdir(dirname(__FILE__) . '/..');
		$cwd = getcwd();
		$pdo = getDBHandler();
		$this->assertType('PDO', $pdo, 'PDO object is not of type PDO');;
		
		// Test create PDO externally
		$new_pdo = getDBHandler($pdo);
		$this->assertSame($pdo, $new_pdo, 'PDO objects are not identical');
	}

	/**
	 * Tests queryDB()
	 */
	public function testQueryDB()
	{
		$db = array('host' => 'localhost', 'user' => 'ud_tester', 'pass' => 'PXhu6u6-)', 'db' => 'TEST_user_directory');
		$dsn = sprintf('mysql:dbname=%s;host=%s;', $db['db'], $db['host']);
        $pdo = new PDO($dsn, $db['user'], $db['pass']);        
        getDBHandler($pdo);

        // Test insert
        $username = uniqid();
		queryDB('INSERT INTO users (username, password) VALUES (\'' . $username . '\', \'' . uniqid() . '\')');

		// Test select w/o parameters
		$stmt = queryDB('SELECT * FROM users WHERE username=\'' . $username . '\'');
		$userInfo = $stmt->fetchObject();
		$this->assertNotNull($userInfo, 'Unsuccessful query');
		$this->assertSame($username, $userInfo->username);
	
		// Test select w/ parameters
		$stmt = queryDB('SELECT * FROM users WHERE username=?', array($username));
		$userInfo = $stmt->fetchObject();
		$this->assertNotNull($userInfo, 'Unsuccessful query');
		$this->assertSame($username, $userInfo->username);
		
		// Test select w/ malformed SQL
		$this->setExpectedException('MyDBException');
		$stmt = queryDB('SELECT * FROM usersasdf WHERE username=?', array($username));
	}	
}

