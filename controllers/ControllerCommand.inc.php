<?php

interface ControllerCommand
{
	public function execute($action);
}

class ControllerCommandFactory
{
	private function __construct() { }
	
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

		throw new Exception('No implementation found for ' . $action);
	}
}