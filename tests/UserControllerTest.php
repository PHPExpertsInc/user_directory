<?php

require_once 'controllers/UserController.inc.php';
require_once 'UserManagerTest.php';
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * UserController test case.
 */
class UserControllerTest extends PHPUnit_Framework_TestCase
{	
	/**
	 * @var UserController
	 */
	private $UserController;

	private function myCreateUserSessionTest()
	{
		$this->assertTrue(session_id() != '', 'session was not created after successful registration');
		$this->assertArrayHasKey('userInfo', $_SESSION, 'userInfo is not set in $_SESSION');
		$this->assertType('UserInfoStruct', $_SESSION['userInfo'], '$_SESSION[\'userInfo\'] is not a UserInfoStruct object');
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
		session_destroy();
		$this->UserController = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Tests MyDBException->__construct()
	 * 
	 */
	
	/**
	 * Tests UserController->__construct()
	 * 
	 * @covers UserController::__construct
	 */
	public function test__construct()
	{
		$this->UserController->__construct();	
	}

	/**
	 * Tests UserController->register()
	 * 
	 * @covers UserController::register
	 */
	public function testRegister()
	{
		// Test without providing any data.
		$this->assertFalse($this->UserController->register(), 'worked without any input :o');
	
		$_POST['profile'] = true;
		// Test empty input
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
	 */
	public function testLogin()
	{
		$_POST['login'] = true;
		$old_POST = $_POST;
		$_POST = array();
		
		// Test login wihout data
		$this->assertFalse($this->UserController->login(), 'logged in without any input.');
		
		// Test login with missing password
		$_POST = $old_POST;
		$_POST['password'] = '';
		$this->assertSame(UserManager::ERROR_BLANK_PASS, $this->UserController->login(), 'logged in without a password.');
		
		// Test login with bad password
		$_POST['password'] = uniqid();
		$this->assertSame(UserManager::ERROR_INCORRECT_PASS, $this->UserController->login(), 'logged in with a bad password.');
		
		// Test login via SQL inject
		$_POST['password'] = '\' OR 1=1; -- ';
		$this->assertSame(UserManager::ERROR_INCORRECT_PASS, $this->UserController->login(), 'logged in with a bad password.');
		
		// Test valid login
		$_POST = $old_POST;
		$this->assertSame(UserManager::LOGGED_IN, $this->UserController->login(), 'would not log in with right info.');

		$this->myCreateUserSessionTest();
	}
	
	/**
	 * Tests UserController->isLoggedIn
	 * 
	 * @covers UserController::isLoggedIn
	 */
	public function testIsLoggedIn()
	{
		session_destroy();

		// Test while being logged out
		$this->assertFalse($this->UserController->isLoggedIn(), 'returned true when logged out');
		
		// Test while being logged in
		$this->UserController->login();
		$this->assertTrue($this->UserController->isLoggedIn(), 'returned false when logged in');
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
		
		$this->assertContains('Location: http://' . $_SERVER['HTTP_HOST'] . '/user_directory/', headers_list());
		
		// 2. Test with being logged in.
		// Log in.
		$_POST = $old_POST;
		$this->UserController->login();
		
		$users = $this->UserController->browse();
		$lastRegistered = UserManagerTest::getLastRegistered();
		$this->assertType('array', $users, 'result was not an array');
		$this->assertTrue(print_r($lastRegistered, true) == print_r($users[0], true), 'returned incorrect results');
	}
	
	/**
	 * Tests UserController->editProfile()
	 * 
	 * @covers UserController::editProfile
	 */
	public function testEditProfile()
	{
		// TODO Auto-generated UserControllerTest->editProfile()
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
		
		$this->assertContains('Location: http://' . $_SERVER['HTTP_HOST'] . '/user_directory/', headers_list());

		// 2. Test with being logged in.
		$_POST = $old_POST;
		$this->UserController->login();
		
		// 2b. with bad input.
		$_POST['password'] = uniqid();
		$this->assertSame(UserManager::ERROR_PASS_MISMATCH, $this->UserController->editProfile(), 'worked with bad input.');
		
		// 2c. with correct input
		$_POST['password'] = $_POST['confirm'];
		$this->assertSame(UserManager::UPDATED_PROFILE, $this->UserController->editProfile(), 'did not work with correct input');
	}
}

