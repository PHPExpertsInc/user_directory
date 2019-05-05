<?php

// Use composer's autoload.
require_once __DIR__ . '/../vendor/autoload.php';

// Needed to resolve:
// 1) Tests\PHPExperts\UserDirectory\SearchControllerTest::test__construct
//    session_start(): Cannot start session when headers already sent
ob_start();
