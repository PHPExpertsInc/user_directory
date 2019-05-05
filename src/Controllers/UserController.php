<?php
/**
* User Directory
*   Copyright � 2008 Theodore R. Smith <theodore@phpexperts.pro>
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

namespace PHPExperts\UserDirectory\Controllers;

use PHPExperts\UserDirectory\Managers\UserManager;

class UserController implements ControllerCommand
{
    /**
     * @var UserManager
     */
    private $userManager;
    
	/** @var SecurityController **/
	protected $guard;

    public function __construct(SecurityController $guard = null)
    {
        $this->userManager = new UserManager;

		if ($guard === null) { $guard = new SecurityController; }
		$this->guard = $guard;
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

	public function browse()
	{
		$this->guard->ensureHasAccess();

		return $this->userManager->getAllUsers();
	}
    
    public function editProfile()
    {
		$this->guard->ensureHasAccess();

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
   
   	public function execute($action)
   	{
   		$data = false;
		if ($action == 'login')
		{
			$login_status = $this->login();
			$data['login_status'] = $login_status;
		}
		else if ($action == 'register')
		{
			$registration_status = $this->register();
			$data['registration_status'] = $registration_status;
		}
		else if ($action == 'browse')
		{
			$data['users'] = $this->browse();
		}
		else if ($action == 'logout')
		{
		    session_destroy();
		    $data['login_status'] = $data['registration_status'] = '';
		}
		else if ($action == 'edit_profile')
		{
		    $data['view_file'] = 'profile.tpl.php';
		    $data['registration_status'] = $this->editProfile();
		}

		return $data;
	}
}
