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
use PHPExperts\MyDB\MyDBI;

class MyDB_Engine_Mock implements MyDBI
{
    // Basic SQL functions
    public function query($sql, array $params = null)
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    public function fetchArray()
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    public function fetchObject($objectType = '\stdClass')
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    // Transactional SQL functions
    public function beginTransaction()
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    public function commit()
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    public function rollback()
    {
        throw new Exception('Hit ' . __METHOD__);
    }

    // Write SQL functions
    public function lastInsertId()
    {
        throw new Exception('Hit ' . __METHOD__);
    }
}
