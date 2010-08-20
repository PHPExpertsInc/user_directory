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
?>
<html>
    <head>
        <title>User Directory Unit Tests</title>
        <style type="text/css">
            #test_results { white-space: pre; overflow: auto; }
            .box { border: 2px solid black; margin: 5px; padding: 10px; width: 35em; }
            #coverage { width: 100%; border: none; display: block; height: 100%; }
        </style>
        <script type="text/javascript">
function resize_iframe()
{
    var hmc = document.getElementById("coverage");
         
             hmc.style.height = document.body.clientHeight - hmc.offsetTop - 10;
}
        </script>
    </head>
    <body>
        <h1>User Directory Unit Tests</h1>

        <h2>Test Results:</h2>
        <div class="box">
            <div id="test_results" style="height: 100px">
<?php
readfile('results.txt');
?>
            </div>
        </div>
        <iframe id="coverage" src="coverage/" onload="resize_iframe();"/>
    </body>
</html>
