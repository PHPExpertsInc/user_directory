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

//require_once 'PHPUnit/Framework/TestCase.php';
require_once dirname(__FILE__) . '/../lib/MyDB.inc.php';

/**
 * queryDB() test case.
 */
class MyDatabaseTestSuite extends \PHPUnit\Framework\TestCase
{
	protected $backupGlobals = false;

	/**
	 * @return MyDBConfigStruct
	 */
	public static function getPDOConfig()
	{
		$config = new stdClass;
		$config->engine = 'Mock';
		
		return $config;
	}

    /**
     * @return MyDBConfigStruct
     */
    public static function getRealPDOConfig()
    {
        $configData = json_decode(base64_decode(file_get_contents(__DIR__ . '/../database.config')));
        $configData->database = 'TEST_' . $configData->database;

        return $configData;
    }

	public static function getReplicatedPDOConfig()
	{
	    $basicConfig = self::getRealPDOConfig();

		$config = new stdClass;
		$config->engine = 'PDO';
		
		$readDB = new stdClass;
		$readDB->hostname = $basicConfig->hostname;
		$readDB->username = $basicConfig->username;
		$readDB->password = $basicConfig->password;
		$readDB->database = 'TEST_user_directory';		

		$writeDB = new stdClass;
		$writeDB->hostname = $basicConfig->hostname;
		$writeDB->username = $basicConfig->username;
		$writeDB->password = $basicConfig->password;
		$writeDB->database = 'TEST_user_directory';

		$config->useReplication = true;
		$config->readDB = $readDB;
		$config->writeDB = $writeDB;
		
		return $config;
	}

}

class MyDBHelperFunctionsTest extends \PHPUnit\Framework\TestCase
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
		$this->assertInstanceOf('MyDBI', $pdo, 'PDO object is not of type PDO');;

		// Test with custom config
		$config = MyDatabaseTestSuite::getPDOConfig();
		$new_pdo = getDBHandler($config);
		$this->assertInstanceOf('MyDBI', $new_pdo, 'PDO object is not of type PDO');;
	}

	/**
	 * Tests queryDB()
	 */
	public function testQueryDb()
	{
		$config = MyDatabaseTestSuite::getRealPDOConfig();
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
		$this->expectException('MyDBException');
		$stmt = @queryDB('SELECT * FROM usersasdf WHERE username=?', array($username));
	}
}

class MyDBTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @covers MyDB::loadDB
	 */
	public function testLoadAPdoDb()
	{
		$config = MyDatabaseTestSuite::getRealPDOConfig();
		$this->assertInstanceOf('MyPDO', MyDB::loadDB($config));
	}

	/**
	 * @covers MyDB::loadDB
	 */
	public function testLoadAReplicatedPdoDb()
	{
		$config = MyDatabaseTestSuite::getReplicatedPDOConfig();
		$this->assertInstanceOf('MyReplicatedPDO', MyDB::loadDB($config));
	}
}

class MyPDOTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var MyPDO
	 */
	protected  $MyPDO;

	protected function setUp()
	{
		$config = MyDatabaseTestSuite::getRealPDOConfig();
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
		$this->assertInstanceOf('PDOStatement', $this->MyPDO->query($qs, array($user['name'], $user['pass'])));
	}

	/**
	 * @covers MyPDO::query
	 */
	public function testCanReadData()
	{
		$user = $GLOBALS['user'];
		$qs = 'SELECT * FROM Users WHERE username=?';
		$stmt = $this->MyPDO->query($qs, array($user['name']));
		$this->assertInstanceOf('PDOStatement', $stmt);

		$userInfo = $stmt->fetchObject();
		$this->assertInstanceOf('stdClass', $userInfo);
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
		$this->assertInstanceOf('PDOStatement', $this->MyPDO->query($qs, array($user['name'], $user['pass'])));

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
		$this->assertInternalType('array', $userInfo);
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
		$this->assertInstanceOf('stdClass', $userInfo);
		$this->assertEquals($user['name'], $userInfo->username);
	}
}


class MyReplicatedDBTest extends MyPDOTest
{
	protected function setUp()
	{
		$config = MyDatabaseTestSuite::getReplicatedPDOConfig();
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
