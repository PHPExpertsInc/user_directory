<?php
/************************************
 * User Directory Live Tutorial
 * Goal: Create a PHP site that has user registration.
 * User details should include first and last name and email.
 * Login, forget password, and update your account. When you
 * are logged in you should be able to search or list users
 * with result pagination. The user should be able to sort
 * results by any of the fields.
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

require 'lib/MyDB.inc.php';
 
function __autoload($name)
{
	if (strpos($name, 'Controller') !== false)
	{
		require 'controllers/' . $name . '.inc.php';
	}
	else if (strpos($name, 'Manager') !== false)
	{
		require 'managers/' . $name . '.inc.php';
	}
	else
	{
		if (file_exists('lib/' . $name . '.inc.php'))
		{
			require 'lib/' . $name . '.inc.php';
		}
	}
}
 
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$view = isset($_GET['view']) ? $_GET['view'] : 'index';

$view_file = 'views/' . $view . '.tpl.php';

if (!file_exists($view_file))
{
    $view_file = 'views/404.tpl.php';
}

if ($view == 'browse')
{
    $action = 'browse';
}
else if ($view == 'search')
{
    $action = 'search';
}

// Initialize form variables
$result = $username = $password = $confirm = $firstName = $lastName = $email = '';

session_start();

if (SecurityController::isLoggedIn())
{
	$login_status = UserManager::LOGGED_IN;
}

$data = ControllerCommandFactory::execute($action);

// Extract $data to global namespace.
if (!is_null($data)) { extract($data); }
require 'views/header.tpl.php';
require $view_file;
require 'views/footer.tpl.php';
