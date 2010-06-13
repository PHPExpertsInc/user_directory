<?php

require_once 'PHPUnit/Framework/TestSuite.php';

require_once 'lib/MyDB.inc.php';
require_once 'lib/UserInfoStruct.inc.php';
require_once 'lib/ViewCommandFactory.inc.php';

require_once 'tests/MyDatabaseTest.php';
require_once 'tests/SearchControllerTest.php';
require_once 'tests/SecurityControllerTest.php';
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
	public function __construct()
	{
		ob_start();
		$this->setName('UserDirectoryTests');
		$this->addTestSuite('MyDatabaseTest');
		$this->addTestSuite('UserManagerTest');
		$this->addTestSuite('SecurityControllerTest');
		$this->addTestSuite('UserControllerTest');
		$this->addTestSuite('SearchControllerTest');
	}
	
	/**
	 * Creates the suite.
	 */
	public static function suite() {
		return new self ( );
	}
}
// @codeCoverageIgnoreStop
