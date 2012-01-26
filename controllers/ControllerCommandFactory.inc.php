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

class ControllerCommandFactory
{
	const ERROR_INVALID_ACTION = 601;
	
	// @codeCoverageIgnoreStart
	private function __construct() { }
	// @codeCoverageIgnoreStop

	public static function execute($action)
	{
		if ($action == '') { return null; }
		$controllers = array('UserController', 'SearchController', 'SecurityController');
		foreach ($controllers as $c)
		{
			$controller = new $c;
			if (($output = $controller->execute($action)) !== false)
			{
				return $output;
			}
		}

		throw new Exception('No implementation found for ' . $action, self::ERROR_INVALID_ACTION);
	}
}
