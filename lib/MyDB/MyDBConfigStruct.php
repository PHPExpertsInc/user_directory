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

use stdClass;

class MyDBConfigStruct
{
    public $hostname;
    public $port = 3306;
    public $username;
    public $password;
    public $database;

    /**
     * Converts a stdClass object to MyDBConfigStruct object
     *
     * @param stdClass $config
     * @return MyDBConfigStruct
     */
    public static function fromStd(stdClass $config)
    {
        $dbConfig = new MyDBConfigStruct;
        $params = get_object_vars($config);

        unset($config);

        foreach ($params as $param => $value)
        {
            if (property_exists($dbConfig, $param))
            {
                $dbConfig->$param = $value;
            }
        }

        return $dbConfig;
    }
}
