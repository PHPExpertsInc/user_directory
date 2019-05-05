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

namespace PHPExperts\UserDirectory\Controllers;

use Exception;

class ControllerCommandFactory
{
    const ERROR_INVALID_ACTION = 601;

    // @codeCoverageIgnoreStart
    private function __construct()
    {
    }

    // @codeCoverageIgnoreStop

    public static function execute($action)
    {
        if ($action == '') {
            return null;
        }
        $controllers = array('UserController', 'SearchController', 'SecurityController');
        foreach ($controllers as $c) {
            $className  = 'PHPExperts\UserDirectory\Controllers\\' . $c;
            $controller = new $className();
            if (($output = $controller->execute($action)) !== false) {
                return $output;
            }
        }

        throw new Exception('No implementation found for ' . $action, self::ERROR_INVALID_ACTION);
    }
}
