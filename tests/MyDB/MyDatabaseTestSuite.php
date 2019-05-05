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

namespace Tests\PHPExperts\MyDB;

use PHPExperts\MyDB\MyDBConfigStruct;
use PHPUnit\Framework\TestCase;
use stdClass;

class MyDatabaseTestSuite extends TestCase
{
    protected $backupGlobals = false;

    /**
     * @return MyDBConfigStruct|stdClass
     */
    public static function getPDOConfig()
    {
        $config         = new stdClass();
        $config->engine = 'Mock';

        return $config;
    }

    /**
     * @return MyDBConfigStruct
     */
    public static function getRealPDOConfig()
    {
        $configData           = json_decode(base64_decode(file_get_contents(__DIR__ . '/../../database.config')));
        $configData->database = 'TEST_' . $configData->database;

        return $configData;
    }

    public static function getReplicatedPDOConfig()
    {
        $basicConfig = self::getRealPDOConfig();

        $config         = new stdClass();
        $config->engine = 'PDO';

        $readDB           = new stdClass();
        $readDB->hostname = $basicConfig->hostname;
        $readDB->username = $basicConfig->username;
        $readDB->password = $basicConfig->password;
        $readDB->database = 'TEST_user_directory';

        $writeDB           = new stdClass();
        $writeDB->hostname = $basicConfig->hostname;
        $writeDB->username = $basicConfig->username;
        $writeDB->password = $basicConfig->password;
        $writeDB->database = 'TEST_user_directory';

        $config->useReplication = true;
        $config->readDB         = $readDB;
        $config->writeDB        = $writeDB;

        return $config;
    }
}
