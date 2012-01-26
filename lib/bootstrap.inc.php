<?php
define('APP_PATH', realpath(dirname(__FILE__) . '/../'));
 
require_once APP_PATH . '/lib/MyDB.inc.php';
require_once APP_PATH . '/lib/UserInfoStruct.inc.php';
//require_once 'MyDB/MyDBTest_Helper.inc.php';
require_once 'MyDatabaseTest.php';

function __autoload($name)
{
	if (strpos($name, 'Controller') !== false)
	{
		require APP_PATH . '/controllers/' . $name . '.inc.php';
	}
	else if (strpos($name, 'Manager') !== false)
	{
		require APP_PATH . '/managers/' . $name . '.inc.php';
	}
	else
	{
		if (file_exists('lib/' . $name . '.inc.php'))
		{
			require APP_PATH . '/lib/' . $name . '.inc.php';
		}
	}
}

// Necessary for sessions to work.
ob_start();
chdir(realpath(dirname(__FILE__) . '/../'));
