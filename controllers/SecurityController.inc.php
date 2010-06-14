<?php

class SecurityController implements ControllerCommand
{
	// Singleton
	// @codeCoverageIgnoreStart
	private function __construct() { }
	// @codeCoverageIgnoreStop

	public static function isLoggedIn()
	{
		if (isset($_SESSION['userInfo']) && $_SESSION['userInfo'] instanceof UserInfoStruct)
		{
			return true;
		}
		
		return false;
	}

	public static function ensureHasAccess()
	{
		if (self::isLoggedIn() === false)
		{
			header('Location: http://' . $_SERVER['HTTP_HOST'] . '/user_directory/');
			throw new Exception('User is not logged in', UserManager::NOT_LOGGED_IN);
		}
	}

   	public function execute($action)
   	{
   		return false;
	}
}
