<?php

ob_start();

// Use composer's autoload.
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../lib/bootstrap.inc.php';
//require_once 'PHPUnit/Framework/TestSuite.php';


require_once APP_PATH . '/lib/MyDB.inc.php';
require_once APP_PATH . '/lib/UserInfoStruct.inc.php';
require_once 'mocks.php';
