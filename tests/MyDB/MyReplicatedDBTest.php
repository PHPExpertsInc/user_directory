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

use PHPExperts\MyDB\MyDB;

class MyReplicatedDBTest extends MyPDOTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $config      = MyDatabaseTestSuite::getReplicatedPDOConfig();
        $this->MyPDO = MyDB::loadDB($config);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->MyPDO = null;
    }

    public function testCanInsertData()
    {
        parent::testCanInsertData();
    }

    public function testCanReadData()
    {
        parent::testCanReadData();
    }

    public function testCanUpdateData()
    {
        parent::testCanUpdateData();
    }

    public function testCanFetchAnArray()
    {
        parent::testCanFetchAnArray();
    }

    public function testCanFetchAnObject()
    {
        parent::testCanFetchAnObject();
    }
}
