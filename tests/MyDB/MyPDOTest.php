<?php

/**
 * User Directory
 *   Copyright (c) 2008, 2019 Theodore R. Smith <theodore@phpexperts.pro>
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

class MyPDOTest extends \PHPUnit\Framework\TestCase
{
    private static $userInfo;

    /**
     * @var MyPDO
     */
    protected  $MyPDO;

    protected function setUp()
    {
        $config = MyDatabaseTestSuite::getRealPDOConfig();
        $this->MyPDO = MyDB::loadDB($config);

        if (!self::$userInfo) {
            self::$userInfo = [
                'name' => uniqid(),
                'pass' => uniqid(),
            ];
        }
    }

    protected function tearDown()
    {
        $this->MyPDO = null;
    }

    /**
     * @covers MyPDO::query
     */
    public function testCanInsertData()
    {
        self::$userInfo = [
            'name' => uniqid(),
            'pass' => uniqid(),
        ];

        $qs = 'INSERT INTO Users (username, password) VALUES (?, ?)';
        $this->assertInstanceOf('PDOStatement', $this->MyPDO->query($qs, array(self::$userInfo['name'], self::$userInfo['pass'])));
    }

    /**
     * @covers MyPDO::query
     */
    public function testCanReadData()
    {
        $user = self::$userInfo;
        $qs = 'SELECT * FROM Users WHERE username=?';
        $stmt = $this->MyPDO->query($qs, array($user['name']));
        $this->assertInstanceOf('PDOStatement', $stmt);

        $userInfo = $stmt->fetchObject();
        $this->assertInstanceOf('stdClass', $userInfo);
        $this->assertObjectHasAttribute('username', $userInfo);
        $this->assertEquals($user['name'], $userInfo->username);
    }

    /**
     * @covers MyPDO::query
     */
    public function testCanUpdateData()
    {
        $user = self::$userInfo;
        $user['pass'] = uniqid();
        $GLOBALS['users'] = $user['pass'];

        $qs = 'UPDATE Users SET password=? WHERE username=?';
        $this->assertInstanceOf('PDOStatement', $this->MyPDO->query($qs, array($user['name'], $user['pass'])));

        // Verify
        $this->testCanReadData();
    }

    /**
     * @covers MyPDO::fetchArray
     */
    public function testCanFetchAnArray()
    {
        $user = self::$userInfo;

        $qs = 'SELECT * FROM Users WHERE username=?';
        $this->MyPDO->query($qs, array($user['name']));

        $userInfo = $this->MyPDO->fetchArray();
        $this->assertInternalType('array', $userInfo);
        $this->assertEquals($user['name'], $userInfo['username']);
    }

    /**
     * @covers MyPDO::fetchObject
     */
    public function testCanFetchAnObject()
    {
        $user = self::$userInfo;

        $qs = 'SELECT * FROM Users WHERE username=?';
        $this->MyPDO->query($qs, array($user['name']));

        $userInfo = $this->MyPDO->fetchObject();
        $this->assertInstanceOf('stdClass', $userInfo);
        $this->assertEquals($user['name'], $userInfo->username);
    }
}
