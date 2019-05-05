<?php
/**
 * User Directory
 *   Copyright (c) 2008, 2011, 2019 Theodore R. Smith <theodore@phpexperts.pro>
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

namespace Tests\PHPExperts\UserDirectory\Mocks;

use PHPExperts\UserDirectory\Controllers\SecurityControllerI;
use PHPExperts\UserDirectory\Managers\UserManager;
use RuntimeException;

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
