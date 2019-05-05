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
use PHPExperts\MyDB\MyDB;
use PHPExperts\MyDB\MyDBException;
use PHPExperts\UserDirectory\Managers\UserInfoStruct;
use PHPExperts\UserDirectory\Managers\UserManager;
use PHPUnit\Framework\TestCase;
use Tests\PHPExperts\MyDB\MyDatabaseTestSuite;

class UserManagerTest extends TestCase
{
    /**
     * @var UserManager
     */
    private $UserManager;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->UserManager = new UserManager();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up database
        $DB = MyDB::loadDB(MyDatabaseTestSuite::getRealPDOConfig());

        $DB->beginTransaction();
        $DB->query('DELETE FROM Users');
        $DB->query('DELETE FROM Profiles');
        $DB->commit();

        $this->UserManager = null;
    }

    /**
     * Helper functions.
     *
     * @return UserInfoStruct
     *
     * @throws MyDBException
     */
    public static function getLastRegistered()
    {
        // Get last-inserted user
        $DB = MyDB::loadDB(MyDatabaseTestSuite::getReplicatedPDOConfig());
        $DB->query('SELECT * FROM vw_UserInfo ORDER BY userID DESC LIMIT 1');
        $userInfo = $DB->fetchObject(UserInfoStruct::class);

        return $userInfo;
    }

    /**
     * Registers a totally random user.
     *
     * @param string $password
     */
    private function registerRandomUser($password = null)
    {
        $pass = is_null($password) ? uniqid() : $password;
        $this->UserManager->createProfile(uniqid(), $pass, $pass, uniqid(), uniqid(), uniqid());
    }

    public function test__construct()
    {
        // Test without username
        self::assertInstanceOf(UserManager::class, new UserManager());
    }

    public function testCreateProfile()
    {
        // Generate random data for test
        $ui = new UserInfoStruct();
        foreach ($ui as &$value) {
            $value = uniqid();
        }
        $ui->password = uniqid();

        // Test for blank username
        self::assertEquals($this->UserManager->createProfile('', '', '', '', '', ''), UserManager::ERROR_BLANK_USER, 'Blank user test');

        // Test for blank password
        self::assertEquals($this->UserManager->createProfile($ui->username, '', '', '', '', ''), UserManager::ERROR_BLANK_PASS, 'Blank password test');

        // Test for non-matching passwords
        self::assertEquals($this->UserManager->createProfile($ui->username, $ui->password, '', '', '', ''), UserManager::ERROR_PASS_MISMATCH, 'Password mismatch test');

        // Test for blank first name
        self::assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, '', '', ''), UserManager::ERROR_BLANK_FNAME, 'Blank first name test');

        // Test for blank last name
        self::assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, $ui->firstName, '', ''), UserManager::ERROR_BLANK_LNAME, 'Blank last name test');

        // Test for blank email
        self::assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, $ui->firstName, $ui->lastName, ''), UserManager::ERROR_BLANK_EMAIL, 'Blank email test');

        // Test for succesful registration
        self::assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, $ui->firstName, $ui->lastName, $ui->email), UserManager::REGISTERED, 'Successful registration test');

        // Test for duplicate registration
        self::assertEquals($this->UserManager->createProfile($ui->username, $ui->password, $ui->password, $ui->firstName, $ui->lastName, $ui->email), UserManager::ERROR_USER_EXISTS, 'Duplicate registration test');
    }

    public function testGetUserInfo()
    {
        // Test when there's no _SESSION data.
        try {
            // Since there's no sessio with user data, we expect the exception to be thrown.
            $this->UserManager->getUserInfo();
        } catch (Exception $e) {
            // Handle expected exception
            if ($e->getCode() != UserManager::MISSING_USER_INFO) {
                throw $e;
            }
        }

        // Must run this to register the user and set up the _SESSION for later.
        $this->registerRandomUser();

        $lastRegistered = self::getLastRegistered();
        $userInfo       = $this->UserManager->getUserInfo();
        self::assertInstanceOf(UserInfoStruct::class, $userInfo);

        self::assertTrue(print_r($lastRegistered, true) == print_r($userInfo, true), 'getUserInfo() is not identical to last registration');

        // Test getting userInfo from the session
        $_SESSION['userInfo'] = $lastRegistered;

        $userManager = new UserManager();
        $newUserInfo = $userManager->getUserInfo();
        self::assertTrue(print_r($lastRegistered, true) == print_r($newUserInfo, true), 'getUserInfo() did not retrieve the last registration via the _SESSION data');
    }

    public function testGetAllUsers()
    {
        // Register multiple users
        $rand_count = rand(2, 10);
        for ($a = 0; $a <= $rand_count; ++$a) {
            $this->registerRandomUser();
        }

        $lastRegistered = self::getLastRegistered();

        $info = $this->UserManager->getAllUsers();

        self::assertTrue(is_array($info), 'UserInfo is not an array');
        $count = count($info);
        self::assertTrue($count > 0, 'UserInfo is empty');
        self::assertInstanceOf(UserInfoStruct::class, $info[$count - 1], 'Last UserInfo item is not an UserInfoStruct object');
        self::assertTrue(print_r($lastRegistered, true) == print_r($info[$count - 1], true), 'Last UserInfo item is not the same as the registered user');
    }

    public function testSearchUsers()
    {
        $this->registerRandomUser();
        $lastRegistered = self::getLastRegistered();

        // Find something that we know exists (last registered username)
        $userInfoArray = $this->UserManager->searchUsers(array('username' => $lastRegistered->username));
        self::assertTrue(is_array($userInfoArray));
        self::assertTrue(count($userInfoArray) == 1);
        self::assertSame($lastRegistered->username, $userInfoArray[0]->username);

        // Find something that we know doesn't exist (new uniqid())
        $userInfoArray = $this->UserManager->searchUsers(array('username' => uniqid()));
        self::assertSame(null, $userInfoArray, 'unsuccessful search did not return null');
    }

    public function testSetUsername()
    {
        $username = 'Random User';
        $this->registerRandomUser();
        $this->UserManager->setUsername($username);

        $userInfo = $this->UserManager->getUserInfo();
        self::assertSame($username, $userInfo->username);
    }

    public function testUpdateProfile()
    {
        $this->registerRandomUser();

        // Test for blank username
        self::assertEquals($this->UserManager->updateProfile('', '', '', '', '', ''), UserManager::ERROR_BLANK_USER, 'Blank user test');

        // Test for blank password
        self::assertEquals($this->UserManager->updateProfile('tsmith', '', '', '', '', ''), UserManager::ERROR_BLANK_PASS, 'Blank password test');

        // Test for non-matching passwords
        self::assertEquals($this->UserManager->updateProfile('tsmith', 'potato', '', '', '', ''), UserManager::ERROR_PASS_MISMATCH, 'Password mismatch test');

        // Test for blank first name
        self::assertEquals($this->UserManager->updateProfile('tsmith', 'potato', 'potato', '', '', ''), UserManager::ERROR_BLANK_FNAME, 'Blank first name test');

        // Test for blank last name
        self::assertEquals($this->UserManager->updateProfile('tsmith', 'potato', 'potato', 'Ted', '', ''), UserManager::ERROR_BLANK_LNAME, 'Blank last name test');

        // Test for blank email
        self::assertEquals($this->UserManager->updateProfile('tsmith', 'potato', 'potato', 'Ted', 'Smith', ''), UserManager::ERROR_BLANK_EMAIL, 'Blank email test');

        // Test for succesful registration
        self::assertEquals($this->UserManager->updateProfile('tsmith', 'potato', 'potato', 'Ted', 'Smith', 'tsmith@brokertools.us'), UserManager::UPDATED_PROFILE, 'Successful registration test');

        // Test for duplicate registration
        $this->registerRandomUser();
        try {
            $this->UserManager->updateProfile('tsmith', 'potato', 'potato', 'Ted', 'Smith', 'tsmith@brokertools.us');
        } catch (Exception $e) {
            self::assertSame(101, $e->getCode(), 'inserted a duplicate email address');
        }
    }

    public function testValidatePassword()
    {
        // Test validation without login attempt
        try {
            $this->UserManager->validatePassword(uniqid());
            self::assertTrue(false, 'validating worked without login attempt');
        } catch (Exception $e) {
            self::assertSame(UserManager::ERROR_BLANK_USER, $e->getCode(), 'Incorrect password validated');
        }

        // Make sure we set the password
        $password = uniqid();
        $this->registerRandomUser($password);

        // Test incorrect password
        self::assertSame(UserManager::ERROR_INCORRECT_PASS, $this->UserManager->validatePassword(uniqid()), 'Incorrect password validated');

        // Test correct password
        self::assertSame(UserManager::CORRECT_PASSWORD, $this->UserManager->validatePassword($password), 'Correct password did not validate');
    }
}
