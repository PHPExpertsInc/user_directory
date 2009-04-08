<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */

    $login_potentials = array(MY_USER_LOGGED_IN => 'Successfully logged in.',
                              MYE_USER_BLANK_PASS => 'Error: No password was entered.',
                              MYE_USER_INVALID_USERPASS => 'Error: Invalid username or password.');

    if ($login_status != '')
    {
?>
            <h3><?php echo $login_potentials[$login_status]; ?></h3>
<?php
    }
?>
        <div id="login">
            <form method="post" class="classyform" action="?action=login">
                <fieldset>
                    <legend>Log in</legend>
                    <ul>
                        <li>
                            <label for="login_username" accesskey="u">Username:</label>
                            <input type="text" name="username" id="login_username" value="<?php echo $username; ?>"/>
                        </li>
                        <li>
                            <label for="login_password" accesskey="p">Password:</label>
                            <input type="password" name="password" id="login_password"/>
                        </li>
                        <li>
                            <label>&nbsp;</label>
                            <input type="submit" name="login" value="Login"/>
                        </li>
                    </ul>
                </fieldset>
            </form>
        </div>
