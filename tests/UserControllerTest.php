<?php
/**
* User Directory
*   Copyright (c) 2008, 2019 Theodore R. Smith <theodore@phpexperts.pro>
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

require_once __DIR__ . '/UserManagerTest.php';

class UserControllerTest extends \PHPUnit\Framework\TestCase
{	
	/**
	 * @var UserController
	 */
	private $UserController;
	protected $backupGlobals = false;

	private function myCreateUserSessionTest()
	{
		$this->assertTrue(session_id() != '', 'session was not created after successful registration');
		$this->assertArrayHasKey('userInfo', $_SESSION, 'userInfo is not set in $_SESSION');
		$this->assertInstanceOf('UserInfoStruct', $_SESSION['userInfo'], '$_SESSION[\'userInfo\'] is not a UserInfoStruct object');
	}

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp()
	{
		parent::setUp();

		session_start();

		unset($_SESSION);
		$_SERVER['HTTP_HOST'] = 'localhost';
		$this->UserController = new UserController();
	}

    /**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		if (session_id() != '') { session_destroy(); }
		$this->UserController = null;
		header_remove();

		parent::tearDown ();
	}

	/**
	 * Tests UserController->__construct()
	 *
	 * @covers UserController::__construct
	 */
	public function test__construct()
	{
		$this->assertInstanceOf('UserController', new UserController());
	}

	/**
	 * Tests UserController->register()
	 *
	 * @covers UserController::register
	 * @covers UserController::createUserSession
	 */
	public function testRegister()
	{
		// Test without providing any data.
		$this->assertFalse($this->UserController->register(), 'worked without any input :o');

		$_POST['profile'] = true;
		// Test empty input
		$_POST['username'] = $_POST['password'] = $_POST['confirm'] = $_POST['firstName'] = $_POST['lastName'] = $_POST['email'] = '';
		$this->assertSame(UserManager::ERROR_BLANK_USER, $this->UserController->register(), 'worked with blank input');

		// Set up most data
		$_POST['username'] = uniqid();
		$_POST['password'] = uniqid();
		$_POST['firstName'] = uniqid();
		$_POST['lastName'] = uniqid();
		$_POST['email'] = uniqid();

		// Test with bad input
		$_POST['confirm'] = 'non-matching password';
		$this->assertSame(UserManager::ERROR_PASS_MISMATCH, $this->UserController->register(), 'worked with bad input');

		// Test with good input
		$_POST['confirm'] = $_POST['password'];
		$this->userPassword = $_POST['password'];
		$this->assertSame(UserManager::REGISTERED, $this->UserController->register(), 'did not work with valid input');

		$this->myCreateUserSessionTest();
	}


	/**
	 * Tests UserController->login()
	 *
	 * @covers UserController::login
	 * @covers UserController::createUserSession
	 *
	 */
	public function testLogin()
	{
		$_POST['login'] = true;
		$old_POST = $_POST;
		$_POST = array();

		// Test login wihout data
		if (session_id() != '') { session_destroy(); }
		$this->assertFalse($this->UserController->login(), 'logged in without any input.');

		// Test login with missing password
		$_POST = $old_POST;
		$_POST['password'] = '';
		$this->assertEquals(UserManager::ERROR_BLANK_PASS, $this->UserController->login(), 'logged in without a password.');

		// Test login with bad password
		$_POST['password'] = uniqid();
		$this->assertEquals(UserManager::ERROR_INCORRECT_PASS, $this->UserController->login(), 'logged in with a bad password.');

		// Test login via SQL inject
		$_POST['password'] = '\' OR 1=1; -- ';
		$this->assertEquals(UserManager::ERROR_INCORRECT_PASS, $this->UserController->login(), 'logged in with a bad password.');

		// Test valid login
		$_POST = $old_POST;
		$this->assertEquals(UserManager::LOGGED_IN, $this->UserController->login(), 'would not log in with right info.');

		// Test with session created
		$this->myCreateUserSessionTest();

		// Test with no session created
		session_destroy();
		$this->assertEquals(UserManager::LOGGED_IN, $this->UserController->login(), 'would not log in with right info.');
		$this->myCreateUserSessionTest();

	}

	/**
	 * Tests UserController->browse()
	 *
	 * @covers UserController::browse
	 */
	public function testBrowse()
	{
		// 1. Test without being logged in.
		// Save old user details.
		$old_POST = $_POST;
		$_POST = array();

		try
		{
			$this->UserController->browse();
			$this->assertTrue(false, 'worked without being logged in.');
		}
		catch(Exception $e)
		{
			$this->assertSame(UserManager::NOT_LOGGED_IN, $e->getCode(), 'Didn\'t expect exception "' . $e->getMessage() . '".');
		}

		$headers_list = headers_list();
		if (!empty($headers_list))
		{
			$this->assertContains('Location: http://' . $_SERVER['HTTP_HOST'] . '/user_directory/', $headers_list);
		}

		// 2. Test with being logged in.
		// Log in.
		$_POST = $old_POST;
		$this->UserController->login();

		$users = $this->UserController->browse();
		$lastRegistered = UserManagerTest::getLastRegistered();
		$this->assertInternalType('array', $users, 'result was not an array');
		$this->assertTrue(print_r($lastRegistered, true) == print_r($users[0], true), 'returned incorrect results');
	}

	/**
	 * Tests UserController->editProfile()
	 *
	 * @covers UserController::editProfile
	 */
	public function testEditProfile()
	{
		// 1. Test without being logged in.
		// Save old user details.
		$old_POST = $_POST;
		$_POST = array();

		try
		{
			$this->UserController->editProfile();
			$this->assertTrue(false, 'worked without being logged in.');
		}
		catch(Exception $e)
		{
			$this->assertSame(UserManager::NOT_LOGGED_IN, $e->getCode(), 'Didn\'t expect exception "' . $e->getMessage() . '".');
		}

		$headers_list = headers_list();
		if (!empty($headers_list))
		{
			$this->assertContains('Location: http://' . $_SERVER['HTTP_HOST'] . '/user_directory/', $headers_list);
		}

		// 2. Test with being logged in.
		$_POST = $old_POST;
		$this->UserController->login();

		// 2a. Test with no input
		unset($_POST['profile']);
		unset($_POST['username']);
		unset($_POST['password']);
		$this->assertFalse($this->UserController->editProfile(), 'worked with no input.');

		$_POST = $old_POST;

		// 2b. with bad input.
		$_POST['password'] = uniqid();
		$this->assertEquals(UserManager::ERROR_PASS_MISMATCH, $this->UserController->editProfile(), 'worked with bad input.');

		// 2c. with correct input
		$_POST['password'] = $_POST['confirm'];
		$this->assertSame(UserManager::UPDATED_PROFILE, $this->UserController->editProfile(), 'did not work with correct input');
	}

	/**
	 * @covers UserController::execute
	 */
	public function testHooksIntoControllerCommandPattern()
	{
		$this->assertFalse($this->UserController->execute('non-existing action'));
	}

	/**
	 * @covers UserController::execute
	 */
	public function testHandlesRegisterAction()
	{
		$data = $this->UserController->execute('register');
		$this->assertInternalType('array', $data);
	}

	/**
	 * @covers UserController::execute
	 */
	public function testHandlesLoginAction()
	{
		$data = $this->UserController->execute('login');
		$this->assertInternalType('array', $data);
		$this->assertEquals(UserManager::LOGGED_IN, $data['login_status']);
	}

	/**
	 * @covers UserController::execute
	 */
	public function testHandlesBrowseAction()
	{
		$this->UserController->login();
		$data = $this->UserController->execute('browse');
		$this->assertInternalType('array', $data);
		$this->assertFalse(empty($data['users']));
		$this->assertTrue($data['users'][0]->username == $_POST['username']);
	}

	/**
	 * @covers UserController::execute
	 */
	public function testHandlesEditProfileAction()
	{
		$this->UserController->login();
		$data = $this->UserController->execute('edit_profile');

		$this->assertInternalType('array', $data);
		$this->assertTrue(UserManager::UPDATED_PROFILE == $data['registration_status']);

	}

	/**
	 * @covers UserController::execute
	 */
	public function testHandlesLogoutAction()
	{
		$data = $this->UserController->execute('logout');
		$this->assertEmpty(session_id());
	}
}
