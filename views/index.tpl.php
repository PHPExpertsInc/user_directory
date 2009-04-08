<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

if (!isset($login_status)) { $login_status = ''; }
if (!isset($registration_status)) { $registration_status = ''; }
?>
<script type="text/javascript">
    function show(name)
    {
        element = document.getElementById(name);
        element.style.display = 'block';
    }
</script>
<?php
if ($login_status == MY_USER_LOGGED_IN)
{
?>
            <p>Hi, <?php echo $_SESSION['userInfo']->firstName; ?>. You are now logged in.</p>
<?php
}
?>
            <p>Welcome to the Stargate user directory.</p>
<?php
if ($login_status != MY_USER_LOGGED_IN && $registration_status != MY_USER_REGISTERED)
{
    $instructions = 'Before you get started, be sure to ';

    if ($login_status != MY_USER_LOGGED_IN)
    {
        $instructions .= '<a onclick="show(\'login\'); this.href=\'#\';" href="?view=login" accesskey="l"><strong>l</strong>og in</a>';
    }

    if ($registration_status != MY_USER_REGISTERED)
    {
        $instructions .= ' or <a onclick="show(\'registration\'); this.href=\'#\';" href="?view=profile" accesskey="r"><strong>r</strong>egister</a>';
    }
?>
            <p><?php echo $instructions; ?></p>
<?php
}
    if (isset($login_status) && $login_status == MY_USER_LOGGED_IN)
    {
?>
            <p>You may either <a href="?view=browse" accesskey="b"><strong>b</strong>rowse</a> all of our users or <a href="?view=search" accesskey="s"><strong>s</strong>earch</a> for a specific user.</p>
<?php
    }
?>
            <div>
                <strong>Trivia:</strong>
                <ul>
                    <li>This web application is fully accessible.  Keyboard access keys are provided for most links and text fields.  The access keys are denoted by
                        <span class="accesskey">u</span>nderlined or <strong>b</strong>olded letters.  In Internet Explorer, try ALT+AccessKey; in Firefox, try ALT+SHIFT+AccessKey.</li>
                    <li>JavaScript is not required for this web application, although it is utilized when present.</li>
                    <li>This web application is fully XHTML 1.1 Strict compliant.</li>
                </ul>
            </div>
<?php
if ($login_status != MY_USER_LOGGED_IN)
{
    require 'views/login.tpl.php';
}

if ($login_status != MY_USER_LOGGED_IN && $registration_status != MY_USER_REGISTERED)
{
    require 'views/profile.tpl.php';
}

if ($action != 'login')
{
?>
        <script type="text/javascript">
            // default action:
            document.getElementById('login').style.display = 'none';
        </script>
<?php
}
