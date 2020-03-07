<?php
//
// Template admin utils file
//

require "../../docroot/page.inc";

// open database
$connection = dbsetup();

// file writing
// if (!($fp = fopen($filename, LOCK_EX)))
// {
//   print "Could not open file {$filename} for writing.  Exiting.";
//   exit;
// }

// fwrite($fp, json_encode($row));

// fclose($fp);

print "\n\nComplete.\n";

?>