<?php

/**
 * User Directory
 *   Copyright (c) 2008, 2012, 2019 Theodore R. Smith <theodore@phpexperts.pro>
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

namespace PHPExperts\MyDB;

use Exception;

class MyDBException extends Exception
{
    const BAD_SQL = 101;
    const CANT_LOAD_CONFIG_FILE = 102;
    const BAD_CONFIG_FILE = 103;
    const NO_DB_ENGINE = 104;

    public function __construct($message = null, $code = 0)
    {
        return parent::__construct($message, $code);
    }
}
