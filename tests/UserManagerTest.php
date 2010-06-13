<?php

require_once 'managers/UserManager.inc.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * UserManager test case.
 *
 * @covers MyReplicatedDB
 * @covers MyReplicatedPDO
 */
class UserManagerTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var UserManager
	 */
	private $UserManager;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp()
	{
		parent::setUp ();
		
		$this->UserManager = new UserManager;
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		// Clean up database
		$DB = MyDB::loadDB(MyDatabaseTest::getPDOConfig());
		$DB->query('TRUNCATE Users');
		$DB->query('TRUNCATE Profiles');

		$this->UserManager = null;

		parent::tearDown ();
	}
	
	/**
	 * Helper functions
	 * @return UserInfoStruct
	 */ 
	public static function getLastRegistered()
	{
		// Get last-inserted user
		$DB = MyDB::loadDB(MyDatabaseTest::getReplicatedPDOConfig());
		$DB->query('SELECT * FROM vw_UserInfo ORDER BY userID DESC LIMIT 1');
		$userInfo = $DB->fetchObject('UserInfoStruct');
		
		return $userInfo;
	}

	/**
	 * Registers a totally random user
	 *
	 * @param string $password
	 * @return UserInfoStruct
	 */
	
	private function registerRandomUser($password = null)
	{
		$pass = is_null($password) ? uniqid() : $password;
		$this->UserManager->createProfile(uniqid(), $pass, $pass, uniqid(), uniqid(), uniqid());
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {}

	/**
	 * Tests UserInfoStruct->__construct()
	 *
	 * @covers UserInfoStruct->__construct
	 */
	public function test_userInfoStruct()
	{
		$userInfo = new UserInfoStruct;
		$this->assertTrue(property_exists($userInfo, 'userID'));
		$this->assertTrue(property_exists($userInfo, 'email'));
		$this->assertTrue(property_exists($userInfo, 'firstName'));
		$this->assertTrue(property_exists($userInfo, 'lastName'));
	}

	/**
	 * Tests UserManager->__construct()
	 * 
	 * @covers UserManager::__construct
	 */
	public function test__construct()
	{
		// Test without username
		$this->UserManager->__construct();
	}

	/**
	 * Tests UserManager->createProfile()
	 * 
	 * @covers UserManager::createProfile
	 * @expectedException Exception
	 */
	public function testCreateProfile()
	{
		// Generate random data for test
		$ui = new UserInfoStruct;
		foreach ($ui as &$value)
		{
			$value = uniqid();
		}
		$ui->password = uniqid();
		
		// Test for blank username
		$this->assertEquals($this->UserManager->createProfile('', '', '', '', '', ''), UserManager::ERROR_BLANK_USER, 'Blank user test');

		// Test for blank password
		$this->assertEquals($this->UserManager->createProfile($ui->username, '', '', '', '', ''), UserManager::ERROR_BLANK_PASS, 'Blank password test');

		// Test for non-matching passwords
		$this->assertEquals($this->UserManager->createProfile($ui->username, $ui->password, '', '', '', ''), UserManager::ERROR_PASS_MISMATCH, 'Password mismatch test');

		// Test for blank first name
		$this->assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, '', '', ''), UserManager::ERROR_BLANK_FNAME, 'Blank first name test');

		// Test for blank last name
		$this->assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, $ui->firstName, '', ''), UserManager::ERROR_BLANK_LNAME, 'Blank last name test');
								
		// Test for blank email
		$this->assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, $ui->firstName, $ui->lastName, ''), UserManager::ERROR_BLANK_EMAIL, 'Blank email test');
		
		// Test for succesful registration
		$this->assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, $ui->firstName, $ui->lastName, $ui->email), UserManager::REGISTERED, 'Successful registration test');
		
		// Test for duplicate registration
		$this->assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, $ui->firstName, $ui->lastName, $ui->email), UserManager::ERROR_USER_EXISTS, 'Duplicate registration test');

		// Test for duplicate email
		$this->assertEquals($this->UserManager->createProfile(uniqid(), $ui->password, $ui->password, $ui->firstName, $ui->lastName, $ui->email), UserManager::ERROR_USER_EXISTS, 'Duplicate registration test');
	}
	
	/**
	 * Tests UserManager->getUserInfo()
	 * 
	 * @covers UserManager::getUserInfo
	 */
	public function testGetUserInfo()
	{
                // Test when there's no _SESSION data.
		try
		{
			// Since there's no sessio with user data, we expect the exception to be thrown.
			$this->UserManager->getUserInfo();
		}
		catch (Exception $e)
		{
			// Handle expected exception
			if ($e->getCode() != UserManager::MISSING_USER_INFO)
			{
				throw $e;
			}
		}

		// Must run this to register the user and set up the _SESSION for later.
		$this->registerRandomUser();
		
		$lastRegistered = self::getLastRegistered();
		$userInfo = $this->UserManager->getUserInfo();
		$this->assertType('UserInfoStruct', $userInfo);

		$this->assertTrue(print_r($lastRegistered, true) == print_r($userInfo, true), 'getUserInfo() is not identical to last registration');

		// Test getting userInfo from the session
		$_SESSION['userInfo'] = $lastRegistered;

		$userManager = new UserManager;
		$newUserInfo = $userManager->getUserInfo();
		$this->assertTrue(print_r($lastRegistered, true) == print_r($newUserInfo, true), 'getUserInfo() did not retrieve the last registration via the _SESSION data');
	}
	
	/**
	 * Tests UserManager->getAllUsers()
	 * 
	 * @covers UserManager::getAllUsers
	 */
	public function testGetAllUsers()
	{
		// Register multiple users
		$rand_count = rand(2, 10);
		for ($a = 0; $a <= $rand_count; ++$a)
		{
			$this->registerRandomUser();
		}
		
		$lastRegistered = self::getLastRegistered();
		
		$info = $this->UserManager->getAllUsers();

		$this->assertTrue(is_array($info), 'UserInfo is not an array');
		$count = count($info);
		$this->assertTrue($count > 0, 'UserInfo is empty');
		$this->assertType('UserInfoStruct', $info[$count - 1], 'Last UserInfo item is not an UserInfoStruct object');
		$this->assertTrue(print_r($lastRegistered, true) == print_r($info[$count - 1], true), 'Last UserInfo item is not the same as the registered user');
	}
	
	/**
	 * Tests UserManager->searchUsers()
	 * 
	 * @covers UserManager::searchUsers
	 */
	public function testSearchUsers()
	{
		$this->registerRandomUser();
		$lastRegistered = self::getLastRegistered();
		
		// Find something that we know exists (last registered username)
		$userInfoArray = $this->UserManager->searchUsers(array('username' => $lastRegistered->username));
		$this->assertTrue(is_array($userInfoArray));
		$this->assertTrue(count($userInfoArray) == 1);
		$this->assertSame($lastRegistered->username, $userInfoArray[0]->username);

		// Find something that we know doesn't exist (new uniqid())
		$userInfoArray = $this->UserManager->searchUsers(array('username' => uniqid()));
		$this->assertSame(null, $userInfoArray, 'unsuccessful search did not return null');		
	}
	
	/**
	 * Tests UserManager->setUsername()
	 * 
	 * @covers UserManager::setUsername
	 */
	public function testSetUsername()
	{
		$username = 'Random User';
		$this->registerRandomUser();
		$this->UserManager->setUsername($username);
	
		$userInfo = $this->UserManager->getUserInfo();
		$this->assertSame($username, $userInfo->username);
	}
	
	/**
	 * Tests UserManager->updateProfile()
	 * 
	 * @covers UserManager::updateProfile
	 */
	public function testUpdateProfile()
	{
		$this->registerRandomUser();
		
		// Test for blank username
		$this->assertEquals($this->UserManager->updateProfile('', '', '', '', '', ''), UserManager::ERROR_BLANK_USER, 'Blank user test');

		// Test for blank password
		$this->assertEquals($this->UserManager->updateProfile('tsmith', '', '', '', '', ''), UserManager::ERROR_BLANK_PASS, 'Blank password test');

		// Test for non-matching passwords
		$this->assertEquals($this->UserManager->updateProfile('tsmith', 'potato', '', '', '', ''), UserManager::ERROR_PASS_MISMATCH, 'Password mismatch test');

		// Test for blank first name
		$this->assertEquals($this->UserManager->updateProfile('tsmith', 'potato', 'potato', '', '', ''), UserManager::ERROR_BLANK_FNAME, 'Blank first name test');

		// Test for blank last name
		$this->assertEquals($this->UserManager->updateProfile('tsmith', 'potato', 'potato', 'Ted', '', ''), UserManager::ERROR_BLANK_LNAME, 'Blank last name test');

		// Test for blank email
		$this->assertEquals($this->UserManager->updateProfile('tsmith', 'potato', 'potato', 'Ted', 'Smith', ''), UserManager::ERROR_BLANK_EMAIL, 'Blank email test');

		// Test for succesful registration
		$this->assertEquals($this->UserManager->updateProfile('tsmith', 'potato', 'potato', 'Ted', 'Smith', 'tsmith@brokertools.us'), UserManager::UPDATED_PROFILE, 'Successful registration test');

		// Test for duplicate registration
		$this->registerRandomUser();
		try
		{
			$this->UserManager->updateProfile('tsmith', 'potato', 'potato', 'Ted', 'Smith', 'tsmith@brokertools.us');
		}
		catch(Exception $e)
		{
			$this->assertSame(101, $e->getCode(), 'inserted a duplicate email address');
		}
	}
	
	/**
	 * Tests UserManager->validatePassword()
	 * 
	 * @covers UserManager::validatePassword
	 */
	public function testValidatePassword()
	{
		// Test validation without login attempt
		try
		{
			$this->UserManager->validatePassword(uniqid());
			$this->assertTrue(false, 'validating worked without login attempt');
		}
		catch (Exception $e)
		{
			$this->assertSame(UserManager::ERROR_BLANK_USER, $e->getCode(), 'Incorrect password validated');
		}
		
		// Make sure we set the password
		$password = uniqid();
		$this->registerRandomUser($password);
		
		// Test incorrect password
		$this->assertSame(UserManager::ERROR_INCORRECT_PASS, $this->UserManager->validatePassword(uniqid()), 'Incorrect password validated');

		// Test correct password
		$this->assertSame(UserManager::CORRECT_PASSWORD, $this->UserManager->validatePassword($password), 'Correct password did not validate');
	}

}

