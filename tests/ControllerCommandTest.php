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

class ControllerCommandTest extends \PHPUnit\Framework\TestCase
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
		$this->assertInternalType('array', $data);
		$this->assertTrue(isset($data['login_status']));
		$this->assertEquals($data['login_status'], false);
	}
}

