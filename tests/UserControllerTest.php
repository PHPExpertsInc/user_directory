<?php

require_once 'controllers/UserController.inc.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * UserController test case.
 */
class UserControllerTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var UserController
	 */
	private $UserController;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated UserControllerTest::setUp()
		

		$this->UserController = new UserController(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated UserControllerTest::tearDown()
		

		$this->UserController = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests UserController->__construct()
	 */
	public function test__construct() {
		// TODO Auto-generated UserControllerTest->test__construct()
		$this->markTestIncomplete ( "__construct test not implemented" );
		
		$this->UserController->__construct(/* parameters */);
	
	}
	
	/**
	 * Tests UserController->browse()
	 */
	public function testBrowse() {
		// TODO Auto-generated UserControllerTest->testBrowse()
		$this->markTestIncomplete ( "browse test not implemented" );
		
		$this->UserController->browse(/* parameters */);
	
	}
	
	/**
	 * Tests UserController->editProfile()
	 */
	public function testEditProfile() {
		// TODO Auto-generated UserControllerTest->testEditProfile()
		$this->markTestIncomplete ( "editProfile test not implemented" );
		
		$this->UserController->editProfile(/* parameters */);
	
	}
	
	/**
	 * Tests UserController->login()
	 */
	public function testLogin() {
		// TODO Auto-generated UserControllerTest->testLogin()
		$this->markTestIncomplete ( "login test not implemented" );
		
		$this->UserController->login(/* parameters */);
	
	}
	
	/**
	 * Tests UserController->register()
	 */
	public function testRegister() {
		// TODO Auto-generated UserControllerTest->testRegister()
		$this->markTestIncomplete ( "register test not implemented" );
		
		$this->UserController->register(/* parameters */);
	
	}

}

