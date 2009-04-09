        </div>
        <div id="footer">
        </div>
        <div id="top_nav">
            <ul>
                <li><a href="?" accesskey="h"><span class="accesskey">h</span>ome</a></li>
<?php
global $login_status, $registration_status;

if (isset($login_status) && $login_status == UserManager::LOGGED_IN)
{
?>
                <li><a href="?action=edit_profile" accesskey="t">edi<span class="accesskey">t</span> profile</a></li>
                <li><a href="?action=logout" accesskey="o">l<span class="accesskey">o</span>gout</a></li>
<?php
}
?>
            </ul>
        </div>
    </body>
</html>
