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

?>
        </div>
        <div id="footer">
            <p>
                <a href="http://validator.w3.org/check?uri=referer"><img
                   src="http://www.w3.org/Icons/valid-xhtml10-blue"
                   alt="Valid XHTML 1.0 Strict" height="31" width="88" style="border: 0" /></a>
                <a href="http://jigsaw.w3.org/css-validator/check/referer">
                    <img style="border:0;width:88px;height:31px"
                         src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
                         alt="Valid CSS!" />
                </a>
            </p>
        </div>
        <div id="top_nav">
            <ul>
                <li><a href="?" accesskey="h">Home</a></li>
<?php
global $login_status, $registration_status;

if (isset($login_status) && $login_status == UserManager::LOGGED_IN)
{
?>
                <li><a href="?action=edit_profile" accesskey="e">Edit Profile</a></li>
                <li><a href="?action=logout" accesskey="l">Logout</a></li>
<?php
}
?>
            </ul>
        </div>
    </body>
</html>
