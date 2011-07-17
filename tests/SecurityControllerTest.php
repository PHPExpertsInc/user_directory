<?php
/**
* User Directory
*   Copyright © 2008 Theodore R. Smith <theodore@phpexperts.pro>
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

require_once dirname(__FILE__) . '/../controllers/ControllerCommand.inc.php';
require_once dirname(__FILE__) . '/../managers/UserManager.inc.php';
require_once dirname(__FILE__) . '/../controllers/SecurityController.inc.php';

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
		$_SERVER['HTTP_HOST'] = 'localhost';
		session_start();
		parent::setUp();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		session_destroy();
		header_remove();
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

