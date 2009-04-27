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
        <div id="action_nav">
            <ul>
                <li><a href="http://7250mhz.brokertools.us/bzrweb/index.py/log/user_directory/head">View Source</a></li>
                <li><a href="user_directory.tar.gz">Download</a></li>
                <li><a href="docs/">Docs</a>
<?php
global $login_status;

if (isset($login_status) && $login_status == UserManager::LOGGED_IN)
{
?>
                <li><a href="?view=browse" accesskey="h">browse</a></li>
                <li><a href="?view=search" accesskey="s">search</a></li>
<?php
}
?>
            </ul>
        </div>
        <div id="header">
            <h1>Stargate User Directory</h1>
            <h4>via server <?php echo $_SERVER['SERVER_ADDR']; ?></h4>
        </div>
        <div id="main_content">
