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

namespace Tests\PHPExperts\UserDirectory;

use Exception;
use PHPExperts\UserDirectory\Controllers\ControllerCommandFactory;
use PHPUnit\Framework\TestCase;

class ControllerCommandTest extends TestCase
{
    public function testHandleMissingCommand()
    {
        self::assertNull(ControllerCommandFactory::execute(''));
    }

    public function testHandleInvalidCommand()
    {
        try {
            ControllerCommandFactory::execute('this is an invalid action, hopefully');
        } catch (Exception $e) {
            self::assertEquals($e->getCode(), ControllerCommandFactory::ERROR_INVALID_ACTION);
        }
    }

    public function testExecuteValidCommand()
    {
        $data = ControllerCommandFactory::execute('login');
        self::assertIsArray($data);
        self::assertTrue(isset($data['login_status']));
        self::assertEquals($data['login_status'], false);
    }
}
