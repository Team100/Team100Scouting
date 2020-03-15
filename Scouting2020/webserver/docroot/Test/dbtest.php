<html>
<title>MySQL Database Test</title>
<body>
<h1>Database Test</h1>

Testing database connection. Entering a PHP block which will call the database.  You should see
an additional statement confirming the test passed...
<p>

<?php

// find database params
require "../params.inc";

// get connection
if(!($connection = @ mysqli_connect($dbhost,$dbuser,$dbpass, $dbname)))
   die("Database Error:" . mysqli_connect_errno() . " : " . mysqli_connect_error());
else
   print "<br>If we made it here, we have successfully connected to the database.<br>";

  ?>
</body>
</html>