<?php

require_once 'PHPUnit/Framework/TestSuite.php';

require_once 'tests/MyDatabaseTest.php';
require_once 'tests/SearchControllerTest.php';
require_once 'tests/UserControllerTest.php';
require_once 'tests/UserManagerTest.php';

/**
 * Static test suite.
 */
// @codeCoverageIgnoreStart
class UserDirectoryTests extends PHPUnit_Framework_TestSuite {
	 protected $topTestSuite = true;
	/**
	 * Constructs the test suite handler.
	 */
	public function __construct() {
		ob_start();
		$this->setName ( 'UserDirectoryTests' );
		$this->addTestSuite('MyDatabaseTest');
/*
		// Load PDO
		$db = array('host' => 'localhost', 'user' => 'ud_tester', 'pass' => 'PXhu6u6-)', 'db' => 'TEST_user_directory');
		$dsn = sprintf('mysql:dbname=%s;host=%s;', $db['db'], $db['host']);
        $pdo = new PDO($dsn, $db['user'], $db['pass']);        
        getDBHandler($pdo);
*/		
		$this->addTestSuite('UserManagerTest');
		$this->addTestSuite('UserControllerTest');
		$this->addTestSuite('SearchControllerTest');
		PHPUnit_Util_Filter::removeDirectoryFromWhitelist('tests');
	}
	
	/**
	 * Creates the suite.
	 */
	public static function suite() {
		return new self ( );
	}
}
// @codeCoverageIgnoreStop
