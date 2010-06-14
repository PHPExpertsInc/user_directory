<?php

require_once 'controllers/ControllerCommand.inc.php';
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * SearchController test case.
 */
class ControllerCommandTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers ControllerCommandFactory::execute
	 */
	public function testHandleMissingCommand()
	{
		$this->assertNull(ControllerCommandFactory::execute(''));
	}

	public function testHandleInvalidCommand()
	{
		try
		{
			ControllerCommandFactory::execute('this is an invalid action, hopefully');
		}
		catch (Exception $e)
		{
			$this->assertEquals($e->getCode(), ControllerCommandFactory::ERROR_INVALID_ACTION);
		}
	}
	
	public function testExecuteValidCommand()
	{
		$data = ControllerCommandFactory::execute('login');
		$this->assertType('array', $data);
		$this->assertTrue(isset($data['login_status']));
		$this->assertEquals($data['login_status'], false);
	}
}

