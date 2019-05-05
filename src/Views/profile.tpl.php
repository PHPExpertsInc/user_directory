<?php
/**
* User Directory
*   Copyright (c) 2010, 2019 Theodore R. Smith <theodore@phpexperts.pro>
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

global $action, $registration_status, $username;
?>
        <div id="profile">
<?php

if (isset($_SESSION['userInfo']))
{
	$username = $_SESSION['userInfo']->username;
	$firstName = $_SESSION['userInfo']->firstName;
	$lastName = $_SESSION['userInfo']->lastName;
	$email = $_SESSION['userInfo']->email;
}

$registration_potentials = array(UserManager::LOGGED_IN => 'Successfully registered.',
                                 UserManager::UPDATED_PROFILE => 'Profile successfully updated.', 
                                 UserManager::ERROR_BLANK_USER => 'A username is required.',
                                 UserManager::ERROR_BLANK_PASS => 'Error: No password was entered.',
                                 UserManager::ERROR_BLANK_FNAME => 'Error: First name is required.',
                                 UserManager::ERROR_BLANK_LNAME => 'Error: Last name is required.',
                                 UserManager::ERROR_BLANK_EMAIL => 'Error: Email is required.',
                                 UserManager::ERROR_USER_EXISTS => 'Error: Username exists. Please try again.',
                                 UserManager::ERROR_PASS_MISMATCH => 'Error: The passwords do not match.');


if ($registration_status != '')
{
?>
            <h3><?php echo $registration_potentials[$registration_status]; ?></h3>
<?php
}
?>
            <form method="post" class="classyform" action="?action=<?php echo isset($action) && $action == 'edit_profile' ? $action : 'register'; ?>">
                <fieldset>
                    <legend><?php echo isset($action) && $action == 'edit_profile' ? 'Edit Profile' : 'Register'; ?></legend>
                    <ul>
                        <li>
                            <label for="register_username" accesskey="u">Username:</label>
                            <input type="text" name="username" id="register_username" value="<?php echo $username; ?>"<?php echo isset($action) && $action == 'edit_profile' ? ' readonly="readonly"' : ''; ?>/>
                        </li>
                        <li>
                            <label for="register_password" accesskey="p">Password:</label>
                            <input type="password" name="password" id="register_password"/>
                        </li>
                        <li>
                            <label for="confirm" accesskey="c">Confirm:</label>
                            <input type="password" name="confirm" id="confirm"/>
                        </li>
                        <li>
                            <label for="firstname" accesskey="f">First name:</label>
                            <input type="text" name="firstName" id="firstname" value="<?php echo $firstName; ?>"/>
                        </li>
                        <li>
                            <label for="lastname" accesskey="l">Last name:</label>
                            <input type="text" name="lastName" id="lastname" value="<?php echo $lastName; ?>"/>
                        </li>
                        <li>
                            <label for="email" accesskey="e">Email:</label>
                            <input type="text" name="email" id="email" value="<?php echo $email; ?>"/>
                        </li>
                        <li>
                            <label>&nbsp;</label>
                            <input type="submit" name="profile" value="Submit"/>
                        </li>
                    </ul>
                </fieldset>
            </form>
        </div>
