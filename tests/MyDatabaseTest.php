<?php

require_once 'PHPUnit/Framework/TestCase.php';

require_once 'lib/MyDatabase.inc.php';

/**
 * queryDB() test case.
 */
class MyDatabaseTest extends PHPUnit_Framework_TestSuite
{
	protected $backupGlobals = false;

	public function __construct()
	{
		ob_start();
		$this->setName('MyDatabaseTest');
		$this->addTestSuite('MyDBHelperFunctionsTest');
		$this->addTestSuite('MyDBTest');
		$this->addTestSuite('MyPDOTest');
		$this->addTestSuite('MyReplicatedDBTest');
	}

	public static function suite()
	{
		return new self();
	}

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

}

class MyDBHelperFunctionsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests getDBHandler()
	 * 
	 * @covers MyDBException
	 */
	public function testGetDbHandler()
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
		$config = MyDatabaseTest::getPDOConfig();
		$new_pdo = getDBHandler($config);
		$this->assertType('MyDBI', $new_pdo, 'PDO object is not of type PDO');;
	}

	/**
	 * Tests queryDB()
	 * 
	 * @covers getQueryDB
	 */
	public function testQueryDb()
	{
		$config = MyDatabaseTest::getPDOConfig();
	     getDBHandler($config);

        	// Test insert
		$username = uniqid();
		queryDB('INSERT INTO Users (username, password) VALUES (\'' . $username . '\', \'' . uniqid() . '\')');

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

class MyDBTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers MyDB::loadDB
	 */
	public function testLoadAPdoDB()
	{
		$config = MyDatabaseTest::getPDOConfig();
		$this->assertType('MyPDO', MyDB::loadDB($config));
	}

	/**
	 * @covers MyDB::loadDB
	 */
	public function testLoadAReplicatedPdoDb()
	{
		$config = MyDatabaseTest::getReplicatedPDOConfig();
		$this->assertType('MyReplicatedPDO', MyDB::loadDB($config));
	}
}

class MyPDOTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var MyPDO
	 */
	protected  $MyPDO;

	protected function setUp()
	{
		$config = MyDatabaseTest::getPDOConfig();
		$this->MyPDO = MyDB::loadDB($config);		
	}
	
	protected function tearDown()
	{
		$this->MyPDO = null;
	}

	/**
	 * @covers MyPDO::query
	 */
	public function testCanInsertData()
	{
		$user['name'] = uniqid();
		$user['pass'] = uniqid();
		
		$GLOBALS['user'] = $user;

		$qs = 'INSERT INTO Users (username, password) VALUES (?, ?)';
		$this->assertType('PDOStatement', $this->MyPDO->query($qs, array($user['name'], $user['pass'])));
	}

	/**
	 * @covers MyPDO::query
	 */
	public function testCanReadData()
	{
		$user = $GLOBALS['user'];
		$qs = 'SELECT * FROM Users WHERE username=?';
		$stmt = $this->MyPDO->query($qs, array($user['name']));
		$this->assertType('PDOStatement', $stmt);

		$userInfo = $stmt->fetchObject();
		$this->assertType('stdClass', $userInfo);
		$this->assertObjectHasAttribute('username', $userInfo);
		$this->assertEquals($user['name'], $userInfo->username);
	}
	
	/**
	 * @covers MyPDO::query
	 */
	public function testCanUpdateData()
	{
		$user = $GLOBALS['user'];
		$user['pass'] = uniqid();
		$GLOBALS['users'] = $user['pass'];

		$qs = 'UPDATE Users SET password=? WHERE username=?';
		$this->assertType('PDOStatement', $this->MyPDO->query($qs, array($user['name'], $user['pass'])));

		// Verify
		$this->testCanReadData();
	}

	/**
	 * @covers MyPDO::fetchArray
	 */
	public function testCanFetchAnArray()
	{
		$user = $GLOBALS['user'];

		$qs = 'SELECT * FROM Users WHERE username=?';
		$this->MyPDO->query($qs, array($user['name']));

		$userInfo = $this->MyPDO->fetchArray();
		$this->assertType('array', $userInfo);
		$this->assertEquals($user['name'], $userInfo['username']);
	}

	/**
	 * @covers MyPDO::fetchObject
	 */
	public function testCanFetchAnObject()
	{
		$user = $GLOBALS['user'];

		$qs = 'SELECT * FROM Users WHERE username=?';
		$this->MyPDO->query($qs, array($user['name']));

		$userInfo = $this->MyPDO->fetchObject();
		$this->assertType('stdClass', $userInfo);
		$this->assertEquals($user['name'], $userInfo->username);
	}
}


class MyReplicatedDBTest extends MyPDOTest
{
	protected function setUp()
	{
		$config = MyDatabaseTest::getReplicatedPDOConfig();
		$this->MyPDO = MyDB::loadDB($config);
	}
	
	protected function tearDown()
	{
		$this->MyPDO = null;
	}

	/**
	 * @covers MyReplicatedDB::query
	 */
	public function testCanInsertData()
	{
		parent::testCanInsertData();
	}

	/**
	 * @covers MyReplicatedDB::query
	 */
	public function testCanReadData()
	{
		parent::testCanReadData();
	}

	/**
	 * @covers MyReplicatedDB::query
	 */
	public function testCanUpdateData()
	{
		parent::testCanUpdateData();
	}

	/**
	 * @covers MyReplicatedDB::fetchArray
	 */
	public function testCanFetchAnArray()
	{
		parent::testCanFetchAnArray();
	}

	/**
	 * @covers MyReplicatedDB::fetchObject
	 */
	public function testCanFetchAnObject()
	{
		parent::testCanFetchAnObject();
	}
}
