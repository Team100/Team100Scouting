<?php
//
// Generates custom parameters files
//  - database schema: compsys-customparams.sql
//  - docroot: params-custom.inc
//
// Generates for teambot, match_team, and match_instance_alliance tables
//

require "../../docroot/page.inc";

// parameters for script
$dbfile = "../../database/schema/compsys-customparams.sql";
$paramsfile = "../../docroot/params-custom.inc";
$custom_param = array("tag","position","used","vargroup","entrytype","dbtype","display","inputlen","maxlen",
     "default_value","list_of_values","tBA_tag","tBA_type");

// vargroup to dbfile mapping array
$vartodb = array("Bot"=>"teambot", "Match"=>"match_team","tBA_Bot"=>"teambot", "tBA_Match"=>"match_instance_alliance");

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
#  generated.  The script should not try to recreate columns if they already
#  exist, but will also not drop columns that are not used any more.  (future feature?)
#
";
fwrite($fdb, $dfiletext);


//
// loop through database dumps for tables, building params at the same time
//

//
// beginning and end of tBA mapping array parameter
//
$begin_tbamap  = "\n\n//\n//\n// tBA - Blue Alliance - custom fields score map\n";
$begin_tbamap .= "//   tBA score fields to custom score fields\n";
$begin_tbamap .= "\$tba_score_to_match_alliance = array (";

print "MAP". $begin_tbamap;

$end_tbamap=" );\n\n";
$tbamap="";


foreach ($vartodb as $vargroup=>$table)
{

  // params and database file headers
  $pfiletext = "\n\n//\n";
  $pfiletext .= "// Parameters for vargroup {$vargroup}\n//\n";

  // add array declaration
  if (substr($vargroup,0,3) == "tBA")
    $pfiletext .= "\$tbaFields[\"" . substr($vargroup,4) . "\"]  = array();\n";
  else
    $pfiletext .= "\$dispfields[\"{$vargroup}\"] = array();\n";
  fwrite($fparams, $pfiletext);

  $dfiletext = "\n\n#\n# Database mods for vargroup {$vargroup}\n";
  fwrite($fdb, $dfiletext);

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

    // if Blue Alliance, make scorefield, otherwise dispfield
    if (substr($vargroup,0,3) == "tBA")
    {
      $pfiletext = '$dispfields["' . $row['vargroup'] . '"][' . $row['position'] . ']' . " = array(\"used\"=>{$used},";
      $pfiletext .= "\"tag\"=>\"{$row['tag']}\", ";
      $pfiletext .= "\"tBAtag\"=>\"{$row['tBA_tag']}\", \"display\"=>\"{$row['display']}\", \"maxlen\"=>{$row['maxlen']} );\r\n";

      // add entry to tbamap
      $tbamap .= ",\n  \"{$row['tBA_tag']}\"=>\"{$row['tag']}\"";
    }
    else
    {
      $pfiletext = '$dispfields["' . $row['vargroup'] . '"][' . $row['position'] . ']' . " = array(\"used\"=>{$used},";
      $pfiletext .= "\"tag\"=>\"{$row['tag']}\", \"display\"=>\"{$row['display']}\", \"inputlen\"=>{$row['inputlen']},";
      $pfiletext .= "\"maxlen\"=>{$row['maxlen']}, \"default_value\"=>\"{$row['default_value']}\");\r\n";
    }
    fwrite($fparams, $pfiletext);

    // database file
    // check if dbtype needs spec
    $dbtype = strtolower($row['dbtype']);
    if ($dbtype == "varchar" || $dbtype == "text")
      $len = "(" . $row['maxlen'] . ")";
    else $len = "";

    $dfiletext = "alter table {$table} add column if not exists ({$row['tag']} {$dbtype} {$len});\r\n";
    fwrite($fdb, $dfiletext);
   } // end of rows

} // end of foreach

//
// wrap up files
//
// chop off first character of $tbamap
$pfiletext = $begin_tbamap . substr($tbamap,1) . $end_tbamap;
$pfiletext .= "//
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

//
// !!! wrap up  by testing include files again.  Because we are generating an include file, if we generate
// a broken one, it can break the system.
//

print "
Generation of files complete.


!!! Now please check that generated params file didn't break the system.

!!! Run this utility again.  If it doesn't run, then the generated file
       broke the system.  Check the include file
       {$paramsfile} and fix the problem. Then regenerate.

";

?>