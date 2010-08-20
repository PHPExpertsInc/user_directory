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

global $action, $login_status, $registration_status;

if (!isset($login_status)) { $login_status = ''; }
if (!isset($registration_status)) { $registration_status = ''; }
?>
<script type="text/javascript">
    function show(name)
    {
        element = document.getElementById(name);

        if (element.style.display == 'none')
        {
            element.style.display = 'block';
        } else {
            element.style.display = 'none';
        }
    }
</script>
<?php
if ($login_status == UserManager::LOGGED_IN)
{
?>
            <p>Hi, <?php echo $_SESSION['userInfo']->firstName; ?>. You are now logged in.</p>
<?php
}
?>
            <p>Welcome to the Portal user directory.</p>
<?php
if ($login_status != UserManager::LOGGED_IN && $registration_status != UserManager::REGISTERED)
{
    $instructions = 'Before you get started, be sure to ';

    if ($login_status != UserManager::LOGGED_IN)
    {
        $instructions .= '<a onclick="show(\'login\'); this.href=\'#\';" href="?view=login" accesskey="l">log in</a>';
    }

    if ($registration_status != UserManager::REGISTERED)
    {
        $instructions .= ' or <a onclick="show(\'profile\'); this.href=\'#\';" href="?view=profile" accesskey="r">register</a>';
    }
?>
            <p><?php echo $instructions; ?></p>
<?php
}
    if (isset($login_status) && $login_status == UserManager::LOGGED_IN)
    {
?>
            <p>You may either <a href="?view=browse" accesskey="b">browse</a> all of our users or <a href="?view=search" accesskey="s">search</a> for a specific user.</p>
<?php
    }
?>
            <div>
                <strong>Trivia:</strong>
                <ul>
                    <li>This web application is fully accessible.  Keyboard access keys are provided for most links and text fields.  The access keys are denoted be
                        underlined or <strong>b</strong>olded letters.  In Internet Explorer, try ALT+AccessKey; in Firefox, try ALT+SHIFT+AccessKey.</li>
                    <li>JavaScript is not required for this web application, although it is utilized when present.</li>
                    <li>This web application is fully XHTML 1.1 Strict compliant.</li>
                </ul>
            </div>
<?php
if ($login_status != UserManager::LOGGED_IN)
{
    require 'views/login.tpl.php';
}

if ($login_status != UserManager::LOGGED_IN && $registration_status != UserManager::REGISTERED)
{
    require 'views/profile.tpl.php';
}

if ($action != 'login')
{
?>
        <script type="text/javascript">
            // default action:
            document.getElementById('login').style.display = 'none';
            document.getElementById('profile').style.display = 'none';
        </script>
<?php
}
