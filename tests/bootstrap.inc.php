<?php

ob_start();

require_once realpath(dirname(__FILE__) . '/../lib/bootstrap.inc.php');
require_once 'PHPUnit/Framework/TestSuite.php';


require_once APP_PATH . '/lib/MyDB.inc.php';

require_once APP_PATH . '/tests/ControllerCommandTest.php';
require_once APP_PATH . '/tests/MyDatabaseTest.php';
require_once APP_PATH . '/tests/SearchControllerTest.php';
require_once APP_PATH . '/tests/SecurityControllerTest.php';
require_once APP_PATH . '/tests/UserControllerTest.php';
require_once APP_PATH . '/tests/UserManagerTest.php';
