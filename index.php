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
 
$action = isset($_GET['action']) ? $_GET['action'] : '';
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
$userControl = new UserController;

if (SecurityController::isLoggedIn())
{
	$login_status = UserManager::LOGGED_IN;
}

if ($action == 'login')
{
    $login_status = $userControl->login();
}
else if ($action == 'register')
{
    $registration_status = $userControl->register();
}
else if ($action == 'browse')
{
    $users = $userControl->browse();
}
else if ($action == 'search')
{
    $searchControl = new SearchController;
    $users = $searchControl->search();
    $searchQueryString = htmlspecialchars($searchControl->getSearchQueryString());
}
else if ($action == 'logout')
{
    session_destroy();
    $login_status = $registration_status = '';
}
else if ($action == 'edit_profile')
{
    $view_file = 'views/profile.tpl.php';
    $registration_status = $userControl->editProfile();
}


require 'views/header.tpl.php';
require $view_file;
require 'views/footer.tpl.php';
