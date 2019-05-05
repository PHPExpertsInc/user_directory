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

namespace PHPExperts\MyDB;

use PDOStatement;

abstract class MyReplicatedDB implements MyDBI
{
    /**
     * DB handle that does all the reading.
     *
     * @var MyDBI
     */
    protected $dbReader;

    /**
     * DB handle that does all the writing.
     *
     * @var MyDBI
     */
    protected $dbWriter;

    /**
     * @param string $sql
     * @param array  $params
     *
     * @return PDOStatement
     */
    public function query($sql, array $params = null)
    {
        $operation = strtoupper(substr($sql, 0, 6));

        // Detect read operation
        if ($operation == 'SELECT') {
            return $this->dbReader->query($sql, $params);
        } else {
            return $this->dbWriter->query($sql, $params);
        }
    }

    public function fetchArray()
    {
        return $this->dbReader->fetchArray();
    }

    public function fetchObject($objectType = '\stdClass')
    {
        return $this->dbReader->fetchObject($objectType);
    }

    public function beginTransaction()
    {
        return $this->dbWriter->beginTransaction();
    }

    public function commit()
    {
        return $this->dbWriter->commit();
    }

    public function rollback()
    {
        return $this->dbWriter->rollback();
    }

    public function lastInsertId()
    {
        return $this->dbWriter->lastInsertId();
    }
}
