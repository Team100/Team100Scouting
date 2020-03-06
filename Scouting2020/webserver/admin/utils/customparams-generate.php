<?php
//
// Generates custom parameters files
//  - database schema: compsys-customparams.sql
//  - docroot: params-custom.inc
//

require "../../docroot/page.inc";

// parameters for script
$dbfile = "../../database/schema/compsys-customparams.sql";
$paramsfile = "../../docroot/params-custom.inc";
$custom_param = array("tag","position","used","vargroup","entrytype","dbtype","display","inputlen","maxlen",
     "default_value");

// vargroup to dbfile mapping array
$vartodb = array("Bot"=>"teambot", "Match"=>"match_team");

//
// Inform user
//

print "

Competition System - Custom Parameters Builder

This script creates the params-custom.inc and compsys-customparams.sql files.

";

// open database
$connection = dbsetup();

$displaydate = date("Y-m-d H:i:s", time());

//
// custom params file
//

// open params and db file for writing
if (!($fparams = fopen($paramsfile, 'w')))
{
  print "Could not open file {$paramsfile} for writing.  Exiting.";
  exit;
}

if (!($fdb = fopen($dbfile, 'w')))
{
  print "Could not open file {$dbfile} for writing.  Exiting.";
  exit;
}


$pfiletext = "<?php
//
// Competition System - Custom Parameters
//
// Generated: {$displaydate}
//
";
fwrite($fparams, $pfiletext);

$dfiletext = "#
# Competition System - Custom Parameters Table Additions
#
# Generated: {$displaydate}
#
# Should be run after freshdb creation.  Can also be run as new params are
#  generated.  The script may error is column alterations already exist,
#  but it should make the new ones.
#
";
fwrite($fdb, $dfiletext);


//
// loop through database dumps for tables, building params at the same time
//

foreach ($vartodb as $vargroup=>$table)
{
  //
  // dump database params
  //

  $query = "select ". fields_insert("nameonly",NULL,$custom_param) . " from custom_param ";
  $query .= " where vargroup = '{$vargroup}' order by vargroup, position";
  if (debug()) print "<br>DEBUG-customparams-generate: " . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // get rows and build files
  while($row = mysqli_fetch_array($result))
  {
    // check for used and turn to true/false
    if ($row['used'] == 1) $used="TRUE"; else $used="FALSE";
    $pfiletext = '$dispfields["' . $row['vargroup'] . '"][' . $row['position'] . ']' . " = array(\"used\"=>{$used},";
    $pfiletext .= "\"tag\"=>\"{$row['tag']}\", \"display\"=>\"{$row['display']}\", \"inputlen\"=>{$row['inputlen']},";
    $pfiletext .= "\"maxlen\"=>{$row['maxlen']}, \"default_value\"=>\"{$row['default_value']}\");\r\n";
    fwrite($fparams, $pfiletext);

    // database file
    // check if dbtype needs spec
    $dbtype = strtolower($row['dbtype']);
    if ($dbtype == "varchar" || $dbtype == "text")
      $len = "(" . $row['maxlen'] . ")";
    else $len = "";

    $dfiletext = "alter table {$table} add column if not exists ({$row['tag']} {$dbtype} {$len});\r\n";
    fwrite($fdb, $dfiletext);
   }
} // end of foreach

// wrap up files
$pfiletext = "//
// End of custom generated file
//
?>";
fwrite($fparams, $pfiletext);

$dfiletext = "#
# End of custom generated columns
#
";
fwrite($fdb, $dfiletext);


// close files
fclose($fparams);
fclose($fdb);

print "\nComplete.\n";

?>