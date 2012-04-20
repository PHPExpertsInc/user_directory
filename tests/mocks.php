<?php
/**
* User Directory
*   Copyright(c) 2008, 2011 Theodore R. Smith <theodore@phpexperts.pro>
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

class SecurityControllerMock implements SecurityControllerI
{
	public $isLoggedIn = true;
	public function isLoggedIn()
	{ 
		return $this->isLoggedIn;
	}

	public function ensureHasAccess()
	{
		if (!$this->isLoggedIn())
		{
			throw new RuntimeException('User is not logged in', UserManager::NOT_LOGGED_IN);
		}
	}

	public function execute($action)
	{
		
	}
}

class UserManagerMock implements UserManagerI
{
	public function setUsername($username)
	{
		throw new Exception("Hit " . __METHOD__);
	}

	public function getUserInfo()
	{
		throw new Exception("Hit " . __METHOD__);
	}

	public function createProfile($username, $password, $confirm, $firstName, $lastName, $email)
	{
		throw new Exception("Hit " . __METHOD__);
	}

	public function updateProfile($username, $password, $confirm, $firstName, $lastName, $email)
	{
		throw new Exception("Hit " . __METHOD__);
	}

	public function validatePassword($password)
	{
		throw new Exception("Hit " . __METHOD__);
	}

	public function searchUsers(array $searchParams)
	{
		//print_r($searchParams);
		if (isset($searchParams['username']) && $searchParams['username'] == 'testuser')
		{
			$user = new UserInfoStruct;
			$user->userID = 1;
			$user->username = 'testuser';
			$user->firstName = 'Test';
			$user->lastName = 'User';
			$user->email = 'test@user.net';

			$users = array($user);

			return $users;
		}

		return null;
	}

	public function getAllUsers()
	{
		throw new Exception("Hit " . __METHOD__);
	}
}