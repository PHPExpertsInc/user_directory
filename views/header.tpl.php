<?php
/**
* User Directory
*   Copyright (c) 2008, 2019 Theodore R. Smith <theodore@phpexperts.pro>
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

use PHPExperts\UserDirectory\Managers\UserManager;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Portal User Directory</title>
        <link type="text/css" rel="stylesheet" media="all" href="css/main.css"/>
        <script defer="defer" type="text/javascript" src="js/accesskeys.js"></script>
    </head>
    <body>
        <div id="action_nav">
            <ul>
                <li><a href="http://7250mhz.brokertools.us/bzrbrowse/" accesskey="v">View Source</a></li>
                <li><a href="user_directory.tar.gz" accesskey="s">Source code</a></li>
                <li><a href="docs/" accesskey="d">Docs</a></li>
                <li><a href="http://7250mhz.brokertools.us/user_directory/tests/" accesskey="t">Tests</a></li>
<?php
global $login_status;

if (isset($login_status) && $login_status == UserManager::LOGGED_IN)
{
?>
                <li><a href="?view=browse" accesskey="b">Browse</a></li>
                <li><a href="?view=search" accesskey="s">Search</a></li>
<?php
}
?>
            </ul>
        </div>
        <div id="header">
            <h1>Portal User Directory</h1>
            <h4>via server <?php echo $_SERVER['SERVER_ADDR']; ?></h4>
        </div>
        <div id="main_content">
