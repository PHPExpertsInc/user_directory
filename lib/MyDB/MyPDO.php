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

use PDO;
use PDOStatement;

class MyPDO implements MyDBI
{
    /** @var PDO **/
    private $pdo;

    /** @var PDOStatement **/
    private $stmt;

    public function __construct(MyDBConfigStruct $config)
    {
        $dsn = sprintf('mysql:dbname=%s;host=%s;port=%d;', $config->database, $config->hostname, $config->port);
        $this->pdo = new PDO($dsn, $config->username, $config->password);
    }

    // Accessors
    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * Queries the database
     *
     * @param string $sql
     * @param array|null $params
     * @return bool|PDOStatement
     * @throws MyDBException
     */
    public function query($sql, array $params = null)
    {
        $stmt = $this->pdo->prepare($sql);

        if (!$stmt->execute($params))
        {
            // Do NOT show SQL errors (security risk)
            $errorInfo = $stmt->errorInfo();
            error_log(sprintf('SQL error: (%s) - %s',
                $errorInfo[1],
                $errorInfo[2]));
            throw new MyDBException('Caught an SQL error...see error log for more details.', MyDBException::BAD_SQL);
        }

        $this->stmt = $stmt;

        return $stmt;
    }

    public function fetchArray()
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchObject($objectType = '\stdClass')
    {
        return $this->stmt->fetchObject($objectType);
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollBack();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
