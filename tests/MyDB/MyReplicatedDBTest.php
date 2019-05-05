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

namespace Tests\PHPExperts\MyDB;

use PHPExperts\MyDB\MyDB;

class MyReplicatedDBTest extends MyPDOTest
{
    protected function setUp()
    {
        $config = MyDatabaseTestSuite::getReplicatedPDOConfig();
        $this->MyPDO = MyDB::loadDB($config);
    }

    protected function tearDown()
    {
        $this->MyPDO = null;
    }

    /**
     * @covers MyReplicatedDB::query
     */
    public function testCanInsertData()
    {
        parent::testCanInsertData();
    }

    /**
     * @covers MyReplicatedDB::query
     */
    public function testCanReadData()
    {
        parent::testCanReadData();
    }

    /**
     * @covers MyReplicatedDB::query
     */
    public function testCanUpdateData()
    {
        parent::testCanUpdateData();
    }

    /**
     * @covers MyReplicatedDB::fetchArray
     */
    public function testCanFetchAnArray()
    {
        parent::testCanFetchAnArray();
    }

    /**
     * @covers MyReplicatedDB::fetchObject
     */
    public function testCanFetchAnObject()
    {
        parent::testCanFetchAnObject();
    }
}
