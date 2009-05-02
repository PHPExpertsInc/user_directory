<?php

require_once 'managers/UserManager.inc.php';
require_once 'controllers/SecurityController.inc.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * SecurityController test case.
 */
class SecurityControllerTest extends PHPUnit_Framework_TestCase {
	public static function simulateLogin()
	{
		$_SESSION['userInfo'] = new UserInfoStruct;
	}

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp()
	{
		session_start();
		parent::setUp();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		session_destroy();
		parent::tearDown();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct()
	{
	}

	/**
	 * Tests SecurityController::isLoggedIn()
	 * 
	 * @covers SecurityController::isLoggedIn
	 */
	public function testIsLoggedIn()
	{
		// 1. Test with no input
		$this->assertFalse(SecurityController::isLoggedIn(), 'worked with no input.');
		
		// 2. Test with bad input
		$_SESSION['userInfo'] = uniqid();
		$this->assertFalse(SecurityController::isLoggedIn(), 'worked with bad input.');
		
		// 3. Test with good input
		self::simulateLogin();
		$this->assertTrue(SecurityController::isLoggedIn(), 'didn\'t work with good input.');
	}

	/**
	 * Tests SecurityController::ensureHasAccess()
	 * 
	 * @covers SecurityController::ensureHasAccess
	 */
	public function testEnsureHasAccess()
	{
		// 1. Test while not being logged in
		try
		{
			SecurityController::ensureHasAccess();
			$this->assertTrue(false, 'worked without being logged in.');
		}
		catch(Exception $e)
		{
			$this->assertEquals(UserManager::NOT_LOGGED_IN, $e->getCode(), 'Didn\'t expect exception "' . $e->getMessage() . '".');
		}
		
		$headers_list = headers_list();
		if (!empty($headers_list))
		{
			$this->assertContains('Location: http://' . $_SERVER['HTTP_HOST'] . '/user_directory/', $headers_list);
		}
		
		// 2. Test while being logged in
		self::simulateLogin();
		$this->assertEquals(null, SecurityController::ensureHasAccess());
	}
	
}

