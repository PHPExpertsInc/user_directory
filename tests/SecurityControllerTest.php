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
use PHPExperts\UserDirectory\Controllers\SecurityController;
use PHPExperts\UserDirectory\Managers\UserInfoStruct;
use PHPExperts\UserDirectory\Managers\UserManager;
use PHPUnit\Framework\TestCase;

class SecurityControllerTest extends TestCase
{
    /** @var SecurityController * */
    protected $guard;

    public static function simulateLogin()
    {
        $_SESSION['userInfo'] = new UserInfoStruct();
    }

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $_SERVER['HTTP_HOST'] = 'localhost';
        session_start();

        $this->guard = new SecurityController();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        session_destroy();
        header_remove();
    }

    public function testIsLoggedIn()
    {
        // 1. Test with no input
        self::assertFalse($this->guard->isLoggedIn(), 'worked with no input.');

        // 2. Test with bad input
        $_SESSION['userInfo'] = uniqid();
        self::assertFalse($this->guard->isLoggedIn(), 'worked with bad input.');

        // 3. Test with good input
        self::simulateLogin();
        self::assertTrue($this->guard->isLoggedIn(), 'didn\'t work with good input.');
    }

    public function testEnsureHasAccess()
    {
        // 1. Test while not being logged in
        try {
            $this->guard->ensureHasAccess();
            self::assertTrue(false, 'worked without being logged in.');
        } catch (Exception $e) {
            self::assertEquals(UserManager::NOT_LOGGED_IN, $e->getCode(), 'Didn\'t expect exception "' . $e->getMessage() . '".');
        }

        $headers_list = headers_list();
        if (!empty($headers_list)) {
            self::assertContains('Location: http://' . $_SERVER['HTTP_HOST'] . '/user_directory/', $headers_list);
        }

        // 2. Test while being logged in
        self::simulateLogin();
        self::assertEquals(null, $this->guard->ensureHasAccess());
    }
}
