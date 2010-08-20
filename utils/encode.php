#!/bin/env php
<?php
/**
* User Directory
*   Copyright © 2008 Theodore R. Smith <theodore@phpexperts.pro>
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

if (!isset($argv[1]))
{
   echo 'Error: Missing filename to encode.' . "\n";
   exit -1;
}

if (!file_exists($argv[1]))
{
   echo 'Error: File not found.' . "\n";
   exit -1;
}

// Load data
$input = file_get_contents($argv[1]);
$output = base64_encode($input);

$filename = basename($argv[1]) . '.enc';

if (isset($argv[2]))
{
   $filename = $argv[2];
}

$fh = fopen($filename, 'w');
fwrite($fh, $output);
fclose($fh);
