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

use stdClass;

// Factory pattern
class MyDB
{
    // @codeCoverageIgnoreStart
    private function __construct() { }
    // @codeCoverageIgnoreStop

    /**
     * @param stdClass|null $config
     * @param string|null $engineName
     * @return MyDBI
     * @throws MyDBException
     */
    public static function loadDB(stdClass $config = null, string $engineName = null)
    {
        static $_config;

        // Auto-return the last config
        if (is_null($config) && !is_null($_config))
        {
            $config = $_config;
        }

        // Never store passes in plaintext!!
        if (is_null($config))
        {
            if (!file_exists(__DIR__ . '/../../database.config'))
            {
                throw new MyDBException('Couldn\'t find database.config.', MyDBException::CANT_LOAD_CONFIG_FILE);
            }

            $data = file_get_contents(__DIR__ . '/../../database.config');

            if ($data === false)
            {
                throw new MyDBException('Couldn\'t load database.config.', MyDBException::CANT_LOAD_CONFIG_FILE);
            }

            $data = base64_decode($data);
            $config = json_decode($data);

            if ($config === false || is_null($config))
            {
                throw new MyDBException('Couldn\'t successfully parse database.config.', MyDBException::BAD_CONFIG_FILE);
            }
        }

        $_config = $config;

        if (!isset($config->engine))
        {
            throw new MyDBException('database.config: No database engine specified.', MyDBException::NO_DB_ENGINE);
        }

        $useReplication = isset($config->useReplication) ? $config->useReplication : false;
        $className = $engineName ?? 'PHPExperts\MyDB\MyDB_Engine_' . $config->engine;

        if ($useReplication)
        {
            $className = 'PHPExperts\MyDB\MyReplicated' . $config->engine;

            return new $className($config);
        }

        if (!class_exists($className))
        {
            throw new MyDBException('database.config: Could not find the DB Engine ' . $className . '.', MyDBException::NO_DB_ENGINE);
        }

        $dbConfig = MyDBConfigStruct::fromStd($_config);

        return new $className($dbConfig);
    }

    public static function printQuery($query, $params)
    {
        $keys = array();

        # build a regular expression for each parameter
        foreach ($params as $key => &$value) {
            if (is_string($key)) {
                $keys[] = '/:'.$key.'/';
            } else {
                $keys[] = '/[?]/';
            }

            if (is_string($value)) {
                $value = "'$value'";
            }
        }

        $query = preg_replace($keys, $params, $query, 1, $count);
        if (substr($query, -1) != ';') { $query .= ';'; }

        return $query;
    }
}
