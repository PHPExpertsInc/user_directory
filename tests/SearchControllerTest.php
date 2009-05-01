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
		
		$this->SearchController = new SearchController(/* parameters */);
	
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		

		$this->SearchController = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct()
	{
	}
	
	/**
	 * Tests SearchController->__construct()
	 */
	public function test__construct()
	{
		// just make sure nothing goes wrong
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

