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
	global $action, $login_status, $username;
    
	$login_potentials = array(UserManager::LOGGED_IN => 'Successfully logged in.',
                              UserManager::ERROR_BLANK_PASS => 'Error: No password was entered.',
                              UserManager::ERROR_INCORRECT_PASS => 'Error: Invalid username or password.');


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
