<?php

interface ControllerCommand
{
	public function execute($action);
}

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
