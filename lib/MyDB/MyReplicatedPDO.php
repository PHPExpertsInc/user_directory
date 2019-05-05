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

class MyReplicatedPDO extends MyReplicatedDB implements MyDBI
{
    public function __construct($config)
    {
        if (!isset($config->readDB))
        {
            throw new MyDBException('Replication support enabled, but no read database specified', MyDBException::BAD_CONFIG_FILE);
        }

        $readDB = MyDBConfigStruct::fromStd($config->readDB);

        $this->dbReader = new MyPDO($readDB);

        if (isset($config->writeDB))
        {
            $writeDB = MyDBConfigStruct::fromStd($config->writeDB);

            $this->dbWriter = new MyPDO($writeDB);
        }
    }
}
