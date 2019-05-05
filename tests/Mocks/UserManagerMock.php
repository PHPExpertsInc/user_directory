<?php

/**
 * User Directory
 *   Copyright (c) 2008, 2011, 2019 Theodore R. Smith <theodore@phpexperts.pro>
 *   GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *
 *   https://www.phpexperts.pro/
 *   https://gitlab.com/phpexperts/user_directory
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
 */

namespace Tests\PHPExperts\UserDirectory\Mocks;

use Exception;
use PHPExperts\UserDirectory\Managers\UserInfoStruct;
use PHPExperts\UserDirectory\Managers\UserManagerI;

class UserManagerMock implements UserManagerI
{
    public function setUsername($username)
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    public function getUserInfo()
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    public function createProfile($username, $password, $confirm, $firstName, $lastName, $email)
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    public function updateProfile($username, $password, $confirm, $firstName, $lastName, $email)
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    public function validatePassword($password)
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    public function searchUsers(array $searchParams)
    {
        //print_r($searchParams);
        if (isset($searchParams['username']) && $searchParams['username'] == 'testuser') {
            $user            = new UserInfoStruct();
            $user->userID    = 1;
            $user->username  = 'testuser';
            $user->firstName = 'Test';
            $user->lastName  = 'User';
            $user->email     = 'test@user.net';

            $users = array($user);

            return $users;
        }

        return null;
    }

    public function getAllUsers()
    {
        throw new Exception('Hit ' . __METHOD__);
    }
}
