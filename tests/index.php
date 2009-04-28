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
