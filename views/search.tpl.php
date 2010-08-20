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

global $users;

?>
        <div id="search">
            <form method="post" class="classyform" action="?view=search">
                <fieldset>
                    <legend>User Search</legend>
                    <ol>
                        <li>Enter one or more pieces of information.</li>
                        <li>Use '%' for wildcard searches.</li>
                    </ol>
                    <ul>
                        <li>
                            <label for="username" accesskey="u">Username:</label>
                            <input type="text" name="username" id="username"/>
                        </li>
                        <li>
                            <label for="firstName" accesskey="f">First Name:</label>
                            <input type="text" name="firstName" id="firstName"/>
                        </li>
                        <li>
                            <label for="lastName" accesskey="l">Last Name:</label>
                            <input type="text" name="lastName" id="lastName"/>
                        </li>
                        <li>
                            <label for="email" accesskey="e">Email:</label>
                            <input type="text" name="email" id="email"/>
                        </li>
                        <li>
                            <label>&nbsp;</label>
                            <input type="submit" name="search" value="Search"/>
                        </li>
                    </ul>
                </fieldset>
            </form>
        </div>
<?php
if (!empty($users))
{
	require 'views/browse.tpl.php';
}
