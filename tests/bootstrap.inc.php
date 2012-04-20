<?php

ob_start();

require_once realpath(dirname(__FILE__) . '/../lib/bootstrap.inc.php');
//require_once 'PHPUnit/Framework/TestSuite.php';


require_once APP_PATH . '/lib/MyDB.inc.php';
require_once APP_PATH . '/lib/UserInfoStruct.inc.php';
require_once 'mocks.php';
