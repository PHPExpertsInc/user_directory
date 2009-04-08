<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

require_once 'managers/UserManager.inc.php';

class UserController
{
    private $userManager;
    
    public function __construct()
    {
        $this->userManager = new UserManager;
    }
    
    protected function createUserSession()
    {
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
        
        if ($result == MY_USER_REGISTERED)
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

        if (!isset($_POST['password']))
        {
            return MYE_USER_BLANK_PASS;
        }

        /* --- Filter user input --- */
        $username = strip_tags($_POST['username']);
        $password = strip_tags($_POST['password']);

        $this->userManager->setUsername($username);
        $status = $this->userManager->validatePassword($password);
        
        if ($status == MY_USER_LOGGED_IN)
        {
            $this->createUserSession();
        }
        
        return $status;
    }
    
    public function browse()
    {
        return $this->userManager->getAllUsers();
    }
    
    public function editProfile()
    {
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
        
        if ($result == MY_USER_PROFILE_UPDATED)
        {
            $this->createUserSession();
        }

        return $result;
   }
}