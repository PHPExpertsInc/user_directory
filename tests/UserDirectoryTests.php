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

require_once 'PHPUnit/Framework/TestSuite.php';

require_once 'lib/MyDB.inc.php';
require_once 'lib/UserInfoStruct.inc.php';

require_once 'tests/ControllerCommandTest.php';
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
		$this->addTestSuite('ControllerCommandTest');
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
