<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

class MyDBException extends Exception 
{
	const ERROR_BAD_SQL = 101;	
}

function getDBHandler($pdo_in = null)
{
    static $pdo = null;

    if (!is_null($pdo_in))
    {
    	$pdo = $pdo_in;
    }
    
    if (is_null($pdo))
    {
        // Never store passes in plaintext!!
        $db = unserialize(base64_decode(file_get_contents('config.inc.php')));
        $dsn = sprintf('mysql:dbname=%s;host=%s;', $db['db'], $db['host']);
        $pdo = new PDO($dsn, $db['user'], $db['pass']);
    }
    
    return $pdo;
}

function queryDB($sql, $params = null)
{
    $pdo = getDBHandler();

    $stmt = $pdo->prepare($sql);
    
    if (!$stmt->execute($params))
    {
        // Do NOT show SQL errors (security risk)
        $errorInfo = $stmt->errorInfo();
        error_log(sprintf('SQL error: (%s) - %s',
                          $errorInfo[1],
                          $errorInfo[2]));
        throw new MyDBException('Caught an SQL error...see error log for more details.', MyDBException::ERROR_BAD_SQL);
    }

    return $stmt;
}
