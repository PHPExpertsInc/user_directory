<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

require_once 'managers/UserManager.inc.php';

class UserController
{
    /**
     * @var UserManager
     */
    private $userManager;
    
    public function __construct()
    {
        $this->userManager = new UserManager;
    }
    
	protected function createUserSession()
	{
		if (session_id() == '')
		{
			session_start();
		}

		$_SESSION['key'] = uniqid();
		$_SESSION['userInfo'] = $this->userManager->getUserInfo();
    }

    public function register()
    {
        if (!isset($_POST['profile']))
        {
            return false;
        }

        /* --- Filter user input --- */
        $username = strip_tags($_POST['username']);
        $password = strip_tags($_POST['password']);
        $confirm = strip_tags($_POST['confirm']);
        $firstname = strip_tags($_POST['firstName']);
        $lastname = strip_tags($_POST['lastName']);
        $email = strip_tags($_POST['email']);

        $result = $this->userManager->createProfile($username, $password, $confirm, $firstname, $lastname, $email);
        
        if ($result == UserManager::REGISTERED)
        {
            $this->createUserSession();
        }

        return $result;
    }
    
    public function login()
    {
        if (!isset($_POST['login']))
        {
            return false;
        }

        if (!isset($_POST['password']) || $_POST['password'] == '')
        {
            return UserManager::ERROR_BLANK_PASS;
        }

        /* --- Filter user input --- */
        $username = strip_tags($_POST['username']);
        $password = strip_tags($_POST['password']);

        $this->userManager->setUsername($username);
        $status = $this->userManager->validatePassword($password);
        
        if ($status == UserManager::LOGGED_IN)
        {
            $this->createUserSession();
        }
        
        return $status;
    }

	public function isLoggedIn()
	{
		if (isset($_SESSION['userInfo']) && $_SESSION['userInfo'] instanceof UserInfoStruct)
		{
			return true;
		}
		
		return false;
	}
	
	private function ensureHasAccess()
	{
		if ($this->isLoggedIn() === false)
		{
			header('Location: http://' . $_SERVER['HTTP_HOST'] . '/user_directory/');
			throw new Exception('User is not logged in', UserManager::NOT_LOGGED_IN);
		}		
	}

	public function browse()
	{
		$this->ensureHasAccess();

		return $this->userManager->getAllUsers();
	}
    
    public function editProfile()
    {
		$this->ensureHasAccess();

    	   if (!isset($_POST['profile']))
        {
            return false;
        }

        /* --- Filter user input --- */
        $username = strip_tags($_POST['username']);
        $password = strip_tags($_POST['password']);
        $confirm = strip_tags($_POST['confirm']);
        $firstName = strip_tags($_POST['firstName']);
        $lastName = strip_tags($_POST['lastName']);
        $email = strip_tags($_POST['email']);

        $result = $this->userManager->updateProfile($username, $password, $confirm, $firstName, $lastName, $email);
        
        if ($result == UserManager::UPDATED_PROFILE)
        {
            $this->createUserSession();
        }

        return $result;
   }
}