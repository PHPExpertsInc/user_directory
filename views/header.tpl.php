<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Stargate User Directory</title>
        <link type="text/css" rel="stylesheet" media="all" href="css/main.css"/>
        <script defer="defer" type="text/javascript" src="js/accesskeys.js"></script>
    </head>
    <body>
<?php
global $login_status;

if (isset($login_status) && $login_status == UserManager::LOGGED_IN)
{
?>
        <div id="action_nav">
            <ul>
                <li><a href="?view=browse" accesskey="h">browse</a></li>
                <li><a href="?view=search" accesskey="s">search</a></li>
            </ul>
        </div>
<?php
}
?>
        <div id="header">
            <h1>Stargate User Directory</h1>
        </div>
        <div id="main_content">
