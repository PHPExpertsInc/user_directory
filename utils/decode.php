#!/bin/env php
<?php
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

$input = base64_decode(file_get_contents($argv[1]));
$filename = $argv[1] . '.plain';

//print_r(json_decode($input));
//exit;

if (isset($argv[2]))
{
   $filename = $argv[2];
}

$fh = fopen($filename, 'w');
fwrite($fh, $input);
fclose($fh);