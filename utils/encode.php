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

// Load data
$input = file_get_contents($argv[1]);
$data = eval('return ' . $input . ';');

if ($data === false || is_null($data))
{
    echo 'Error: Couldn\'t parse config file.';
    exit -1;
}

$output = base64_encode(var_export($data, true));
$filename = basename($argv[1]) . '.enc';

if (isset($argv[2]))
{
   $filename = $argv[2];
}

$fh = fopen($filename, 'w');
fwrite($fh, $output);
fclose($fh);
