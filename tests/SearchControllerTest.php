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

require_once dirname(__FILE__) . '/../controllers/SearchController.inc.php';
require_once dirname(__FILE__) . '/../controllers/UserController.inc.php';
require_once dirname(__FILE__) . '/../tests/SecurityControllerTest.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * SearchController test case.
 */
class SearchControllerTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var SearchController
	 */
	private $SearchController;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp()
	{
		parent::setUp();

		ob_start();
		session_start();
		
		unset($_SESSION);
		$_SERVER['HTTP_HOST'] = 'localhost';
		
		$this->SearchController = new SearchController();
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		if (session_id() != '') { session_destroy(); }
		$this->SearchController = null;
		
		parent::tearDown();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct()
	{
	}
	
	/**
	 * Tests SearchController->__construct()
	 * 
	 * @covers SearchController::__construct
	 */
	public function test__construct()
	{
		// just make sure nothing goes wrong
		$this->SearchController->__construct();
	}

	/**
	 * Tests SearchController->search()
	 * 
	 * @covers SearchController::search
	 */
	public function testSearch()
	{
		// 1. Test without being logged in
		$old_POST = $_POST;
		$_POST = array();

		try
		{
			$this->SearchController->search();
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
		
		// 2. Test with being logged in
		$_POST = $old_POST;
		$userController = new UserController;
		$userController->login();
		
		// 2a. Test with no input
		$this->assertFalse($this->SearchController->search(), 'worked with no input.');
		
		// 2b. Test with bad input
		$_POST['search'] = true;
		$old_user = $_POST['username'];
		$_POST['username'] = uniqid();
		$_REQUEST = $_POST;
		
		$this->assertNull($this->SearchController->search(), 'worked with bad input.');
		
		// 2c. Test with good input
		$_REQUEST['username'] = $old_user;
		$users = $this->SearchController->search();
		$this->assertType('array', $users);
		$this->assertType('UserInfoStruct', $users[0]);
	}

	/**
	 * @covers SearchController::getSearchQueryString
	 */
	public function testGetSearchQueryString()
	{
		// 1. Test in an improper context.
		$this->assertNull($this->SearchController->getSearchQueryString());

		// 2. Test in a proper context.
		$_GET['page'] = 1;
		$_REQUEST['firstName'] = 'Ted';
		$_POST['firstName'] = 'Ted';

		$userController = new UserController;
		$userController->login();

		$this->SearchController->search();
		$this->assertEquals('username=&firstName=Ted&lastName=&email=', $this->SearchController->getSearchQueryString());
	}

	/**
	 * @covers SearchController::execute
	 */
	public function testHooksIntoControllerCommandPattern()
	{
		$this->assertFalse($this->SearchController->execute('non-existing action'));
	}


	/**
	 * @covers SearchController::execute
	 */
	public function testHandlesSearchAction()
	{
		$_REQUEST['username'] = $_POST['username'];
		$userController = new UserController;
		$userController->login();

		$data = $this->SearchController->execute('search');
		$this->assertType('array', $data);
	}
}

