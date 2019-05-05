<?php
/**
 * User Directory
 *   Copyright (c) 2008, 2019 Theodore R. Smith <theodore@phpexperts.pro>
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

namespace PHPExperts\UserDirectory\Managers;

use Exception;
use function PHPExperts\MyDB\getDBHandler;
use function PHPExperts\MyDB\queryDB;

interface UserManagerI
{
    public function setUsername($username);
    public function getUserInfo();
    public function createProfile($username, $password, $confirm, $firstName, $lastName, $email);
    public function updateProfile($username, $password, $confirm, $firstName, $lastName, $email);
    public function validatePassword($password);
    public function searchUsers(array $searchParams);
    public function getAllUsers();
}
