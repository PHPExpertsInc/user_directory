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
                <li><a href="?action=edit_profile" accesskey="t">Edit Profile</a></li>
                <li><a href="?action=logout" accesskey="l">Logout</a></li>
<?php
}
?>
            </ul>
        </div>
    </body>
</html>
