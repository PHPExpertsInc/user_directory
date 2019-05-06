<?php
/**
* User Directory
*   Copyright � 2008 Theodore R. Smith <theodore@phpexperts.pro>.
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
?>
<html>
    <head>
        <title>User Directory Documents</title>
        <style type="text/css">
.box { float: left; border: 2px solid black; padding: 15px; background: white; margin-right: 20px; }
h1 { margin-top: 0; text-align: center; }
        </style>
    </head>
    <body style="background: #eee">
        <div class="box">
            <h1>Entity Relationship Diagram (ERD)</h1>
            <div style="text-align: center">
                <img src="db_model.png" alt="database model" width="414" height="274"/>
            </div>
        </div>
        <div class="box">
            <h1>Mockup</h1>
            <div style="text-align: center">
                <img src="portal.png" alt="Mockup"/>
            </div>
        </div>
        <div class="box">
<?php
$usecasesHTML = file_get_contents('usecases.html');
echo str_replace("<h2", "<h3", $usecasesHTML);
?>
        </div>
    </body>
</html>
