<?php
/************************************
 * User Directory Live Tutorial
 *
 * Copyright(c) 2008 Theodore R. Smith
 * License: Creative Commons */
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
if (isset($users))
{
	require 'views/browse.tpl.php';
}