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
use PHPExperts\UserDirectory\Controllers\SearchController;
use PHPExperts\UserDirectory\Managers\UserInfoStruct;
use PHPExperts\UserDirectory\Managers\UserManager;
use PHPUnit\Framework\TestCase;
use Tests\PHPExperts\UserDirectory\Mocks\SecurityControllerMock;
use Tests\PHPExperts\UserDirectory\Mocks\UserManagerMock;

class SearchControllerTest extends TestCase
{
    /** @var SearchController */
    private $SearchController;

    /** @var SecurityControllerMock */
    protected $guard;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        session_start();

        unset($_SESSION);
        $_SERVER['HTTP_HOST'] = 'localhost';

        $this->guard            = new SecurityControllerMock();
        $userManager            = new UserManagerMock();
        $this->SearchController = new SearchController($this->guard, $userManager);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if (session_id() != '') {
            session_destroy();
        }
        $this->SearchController = null;
        header_remove();
    }

    public function test__construct()
    {
        // just make sure nothing goes wrong
        self::assertInstanceOf(SearchController::class, new SearchController());
    }

    public function testSearch()
    {
        // 1. Test without being logged in
        // 1a. Simulate a user being logged out.
        $this->guard->isLoggedIn = false;

        $_POST = array();

        try {
            $this->SearchController->search();
            self::assertTrue(false, 'worked without being logged in.');
        } catch (Exception $e) {
            self::assertSame(UserManager::NOT_LOGGED_IN, $e->getCode(), 'Didn\'t expect exception "' . $e->getMessage() . '".');
        }

        $headers_list = headers_list();
        if (!empty($headers_list)) {
            self::assertContains('Location: http://' . $_SERVER['HTTP_HOST'] . '/user_directory/', $headers_list);
        }

        // 2. Test with being logged in
        // 2a. Simulate a user being logged out.
        $this->guard->isLoggedIn = true;

        // 2a. Test with no input
        self::assertFalse($this->SearchController->search(), 'worked with no input.');

        // 2b. Test with bad input
        $_POST['search']   = true;
        $_POST['username'] = uniqid();
        $_REQUEST          = $_POST;

        self::assertNull($this->SearchController->search(), 'worked with bad input.');

        // 2c. Test with good input
        $_REQUEST['username'] = 'testuser';
        $users                = $this->SearchController->search();
        self::assertIsArray($users);
        self::assertInstanceOf(UserInfoStruct::class, $users[0]);
    }

    public function testGetSearchQueryString()
    {
        // 1. Test in an improper context.
        self::assertEmpty($this->SearchController->getSearchQueryString());

        // 2. Test in a proper context.
        $_GET['page']          = 1;
        $_REQUEST['username']  = 'testuser';
        $_POST['username']     = 'testuser';
        $_REQUEST['firstName'] = 'Ted';
        $_POST['firstName']    = 'Ted';

        $this->SearchController->search();
        self::assertEquals('username=testuser&firstName=Ted', $this->SearchController->getSearchQueryString());
    }

    public function testHooksIntoControllerCommandPattern()
    {
        self::assertFalse($this->SearchController->execute('non-existing action'));
    }

    public function testHandlesSearchAction()
    {
        $this->guard->isLoggedIn = true;

        $data = $this->SearchController->execute('search');
        self::assertIsArray($data);
    }
}
