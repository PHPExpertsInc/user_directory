<?php

require_once 'controllers/SearchController.inc.php';

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
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SearchControllerTest::setUp()
		

		$this->SearchController = new SearchController(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SearchControllerTest::tearDown()
		

		$this->SearchController = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SearchController->__construct()
	 */
	public function test__construct() {
		// TODO Auto-generated SearchControllerTest->test__construct()
		$this->SearchController->__construct();
	
	}
	
	/**
	 * Tests SearchController->search()
	 */
	public function testSearch() {
		// TODO Auto-generated SearchControllerTest->testSearch()
		$this->markTestIncomplete ( "search test not implemented" );
		
		$this->SearchController->search(/* parameters */);
	
	}

}

