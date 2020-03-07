<?php
//
// $Revision: 3.0 $
// $Date: 2016/03/14 22:56:41 $
//
// params.inc
//
// Contains all user and admin settable parameters needed by the competition system
//  PLEASE READ through the entire paramaters file to ascertain what is already 
//   parameter-driven.  You may find there is a parameter-based way to control the app
//   to do what you want without needing to write/modify code.
//
// Additional Notes:
// - Configuration of Blue Alliance data loading is generally configured in 
//    bluealliance.inc.  Field mappings are defined there and should generally
//    obviate the need to reprogram when fields change.
//    - There are a couple of Blue Alliance mappings that are handled in this file
//      instead of in bluealliance.inc.  These mappings change every year and involve
//      also changing UI display tags, so there are covered in a different structure
//      than the more simple tag mapping defined in bluealliance.inc.  Because they
//      change the UI, they are defined here.
// 

// Timezone setting
//  -- the string must come from a PHP list found here:
//       http://php.net/manual/en/timezones.php
$sys_time_zone='America/Los_Angeles';

//
// Host team - Team that uses this db
$host_teamnum=100;
//$host_teamnum=4;
$host_team_name="Wildhats";
// don't change next line
$frcteam=sprintf("frc%'04d",$host_teamnum);

//
// The Blue Alliance API id
//   Format is FRC team number:user:version.  Change as needed.
$tba_AppId= $frcteam . ':compsystem:v02';


// Site parameters
//
// site names as in http://site/
if(isset($_SERVER['SERVER_NAME'])) $site = $_SERVER['SERVER_NAME']; else $site = NULL;
// base - everything after site.  Example: /compsys.  Leave at "/" if nothing.
$base = "/";


//
// Custom params limit per type
$custom_params_limit = 40;


// 
// Field positions
//
// Set to TRUE if field positions (where a robot starts and plays) matter in the analysis.  Some years very important, 
//   some years not important at all.  Set to false to skip and field positions will not be used in analysis forms
//
//$field_positions = TRUE;
$field_positions = FALSE;

//
// Field parameters
//
// Notes:
//  Field parameters are intended to be changed every year for a given game
// 
//  display - what is displayed in fields prompts to user
//  inputlen - length of input box
//  maxlen - max length of entry. Browser will not accept more characters.
//  default_value - value placed in input box as a default.  Can be null if no value is expected.
//
//
//   Should we create a "alliance position" switch too?  (for later)
//
// All definted from included db-generated file
require "params-custom.inc";


//
// set max length of Match fields to match the database.  Tablet data longer than this is truncated.
$tablet_max_matchfield = 50;

// map match tablet tags to standard columns
//  Format: tag=>column
$map_match_tags = array ("offensiverating"=>"rating_offense", "defensiverating"=>"rating_defense", "Notes"=>"match_notes");


//
// score breakout
//
// Format is different than the above arrays, as it combines tBA tags for import with 
//  display
// 
// Key:
//  - tBAtag: tag used by tBA.  Used by our system in Blue Alliance Loader
//  - display: Text used to display field in application
//  - column: if needed, column used for database mapping.  For custom fields
//     column is derived from position in ScoreField array.
//
// Note:
//   - To determine which fields are used in a given year, run admin/utils/blue-score.php



$ScoreFields[0] = array("tBAtag"=>"totalPoints", "display"=>"Total Points");
$ScoreFields[1] = array("tBAtag"=>"techFoulCount", "display"=>"Foul Count");
$ScoreFields[2] = array("tBAtag"=>"adjustPoints", "display"=>"Adjust Points");
$ScoreFields[3] = array("tBAtag"=>"capturePoints", "display"=>"Capture Points");
$ScoreFields[4] = array("tBAtag"=>"teleopPoints", "display"=>"TeleOp Points");
$ScoreFields[5] = array("tBAtag"=>"autoPoints", "display"=>"Auto Points");
$ScoreFields[6] = array("tBAtag"=>"breachPoints", "display"=>"Breach Points");
$ScoreFields[7] = array("tBAtag"=>"foulCount", "display"=>"Fouls");
$ScoreFields[8] = array("tBAtag"=>"foulPoints", "display"=>"Fouls Points");
$ScoreFields[9] = array("tBAtag"=>"towerFaceA", "display"=>"Tower Face A");
$ScoreFields[10] = array("tBAtag"=>"towerFaceB", "display"=>"Tower Face B");
$ScoreFields[11] = array("tBAtag"=>"towerFaceC", "display"=>"Tower Face C");
$ScoreFields[12] = array("tBAtag"=>"autoCrossingPoints", "display"=>"Auto Crossing Points");
$ScoreFields[13] = array("tBAtag"=>"autoBoulderPoints", "display"=>"Boulder Points");
$ScoreFields[14] = array("tBAtag"=>"autoBouldersLow", "display"=>"Boulders Low");
$ScoreFields[15] = array("tBAtag"=>"autoBouldersHigh", "display"=>"Bloulders High");
$ScoreFields[16] = array("tBAtag"=>"autoReachPoints", "display"=>"Reach Points");
$ScoreFields[17] = array("tBAtag"=>"teleopCrossingPoints", "display"=>"Crossing Points");
$ScoreFields[18] = array("tBAtag"=>"teleopBoulderPoints", "display"=>"Boulder Points");
$ScoreFields[19] = array("tBAtag"=>"teleopBouldersHigh", "display"=>"Tele Boulders High");
$ScoreFields[20] = array("tBAtag"=>"teleopBouldersLow", "display"=>"Tele Boulder Low");
$ScoreFields[21] = array("tBAtag"=>"teleopDefensesBreached", "display"=>"Tele Denfenses Breached");
$ScoreFields[22] = array("tBAtag"=>"teleopScalePoints", "display"=>"Tele Scale Points");
$ScoreFields[23] = array("tBAtag"=>"teleopTowerCaptured", "display"=>"Tele Tower Captured");
$ScoreFields[24] = array("tBAtag"=>"teleopChallengePoints", "display"=>"Tele Challenge Points");
$ScoreFields[25] = array("tBAtag"=>"towerEndStrength", "display"=>"Tele End Strength");
$ScoreFields[26] = array("tBAtag"=>"robot1Auto", "display"=>"Robot 1 Auto");
$ScoreFields[27] = array("tBAtag"=>"robot2Auto", "display"=>"Robot 2 Auto");
$ScoreFields[28] = array("tBAtag"=>"robot3Auto", "display"=>"Robot 3 Auto");
$ScoreFields[29] = array("tBAtag"=>"position1", "display"=>"Position 1");
$ScoreFields[30] = array("tBAtag"=>"position2", "display"=>"Position 2");
$ScoreFields[31] = array("tBAtag"=>"position3", "display"=>"Position 3");
$ScoreFields[32] = array("tBAtag"=>"position4", "display"=>"Position 4");
$ScoreFields[33] = array("tBAtag"=>"position5", "display"=>"Position 5");
$ScoreFields[34] = array("tBAtag"=>"position1crossings", "display"=>"Position 1 Crossings");
$ScoreFields[35] = array("tBAtag"=>"position2crossings", "display"=>"Position 2 Crossings");
$ScoreFields[36] = array("tBAtag"=>"position3crossings", "display"=>"Position 3 Crossings");
$ScoreFields[37] = array("tBAtag"=>"position4crossings", "display"=>"Position 4 Crossings");
$ScoreFields[38] = array("tBAtag"=>"position5crossings", "display"=>"Position 5 Crossings");
//$ScoreFields[39] = array("tBAtag"=>"", "display"=>"");
//$ScoreFields[40] = array("tBAtag"=>"", "display"=>"");
//$ScoreFields[41] = array("tBAtag"=>"", "display"=>"");
//$ScoreFields[42] = array("tBAtag"=>"", "display"=>"");
//$ScoreFields[43] = array("tBAtag"=>"", "display"=>"");
//$ScoreFields[44] = array("tBAtag"=>"", "display"=>"");
//$ScoreFields[] = array("tBAtag"=>"", "display"=>"");


//
// Ranking Fields (per-team)
//
// Per team ranking fields from FIRST / Blue Alliance, sorted on a per-team basis
//
// Format is different than the above arrays, as it combines tBA tags for import with 
//  display
// 
// Key:
//  - tBApos: position (0,2,..) used in tBA array. Used by our system in Blue Alliance Loader
//  - display: Text used to display field in application
//  - column: if needed, column used for database mapping.  Custom fields start with 
//     f_rankparam, i.e. f_rankparam0
//
// Order in the array is the order the fields will be displayed on teaminfo
//
// NOTES: 
//   - To determine which fields are used in a given year, run admin/utils/blue-ranking.php
//   - to display any of these fields in the rank.php page, go to parameter $rank_column_custom below.
//   - blue alliance loader expects one of the fields to be labeled with column=>teamnum

$RankFields[0] = array("tBApos"=>"1", "column"=>"teamnum", "display"=>"");  // leave display blank for no display
$RankFields[1] = array("tBApos"=>"0", "column"=>"f_ranking", "display"=>"FIRST Rank");
$RankFields[2] = array("tBApos"=>"2", "column"=>"f_rank_score", "display"=>"Ranking Score");
$RankFields[3] = array("tBApos"=>"7", "column"=>"f_record", "display"=>"Record (W-L-T)");
$RankFields[4] = array("tBApos"=>"8", "column"=>"f_games_played", "display"=>"Played");

$RankFields[5] = array("tBApos"=>"3", "column"=>"f_rankparam0", "display"=>"Auto");
$RankFields[6] = array("tBApos"=>"4", "column"=>"f_rankparam1", "display"=>"Scale/Challenge");
$RankFields[7] = array("tBApos"=>"5", "column"=>"f_rankparam2", "display"=>"Goals");
$RankFields[8] = array("tBApos"=>"6", "column"=>"f_rankparam3", "display"=>"Defense");
//$RankFields[9] = array("tBApos"=>"", "column"=>"f_rankparam4", "display"=>"");
//$RankFields[10] = array("tBApos"=>"", "column"=>"f_rankparam5", "display"=>"");
//$RankFields[11] = array("tBApos"=>"", "column"=>"f_rankparam6", "display"=>"");
//$RankFields[12] = array("tBApos"=>"", "column"=>"f_rankparam7", "display"=>"");
//$RankFields[13] = array("tBApos"=>"", "column"=>"f_rankparam8", "display"=>"");


//
// Stats columns display definitions (similar to Ranking below
//
//  Format:
//   key=>array, array of arrays that include:
//     - key: db column name
//     - array:
//       - display text in page disply
//       - heading - heading over rank table
//       - format mask to be used in printf
//       - order: a for ascending sort, d for descending
//
$stats_columns  = array (
  "opr"=>array("display"=>"Offensive Power Rating", "heading"=>"Off Power", "format"=>"%.2f", "order"=>"a"),
  "dpr"=>array("display"=>"Defense Power Rating", "heading"=>"Def Power", "format"=>"%.2f", "order"=>"a"),
  "ccwm"=>array("display"=>"Cont to Winning Margin", "heading"=>"CCW Marg", "format"=>"%.2f", "order"=>"a")
  );

//
// Ranking: custom columns to display
//
//  Format:
//   key=>array, array of arrays that include:
//     - key: db column name
//     - array:
//       - display text in page disply
//       - heading - heading over rank table
//       - format mask to be used in printf
//       - order: a for ascending sort, d for descending
//  

$rank_columns_custom  = array_merge(
    array (
    "f_ranking"=>array("display"=>"FIRST Ranking", "heading"=>"FIRST Rank", "format"=>"%d", "order"=>"a"),
    "f_rank_score"=>array("display"=>"Ranking Score", "heading"=>"Rank Score", "format"=>"%.2f", "order"=>"a")
    // ""=>array("display"=>"", "heading"=>"", "format"=>""),
    ),
  $stats_columns
  );

//
// needs evaluation columns 
//  if any of these columns are null, team is marked with a "needs eval" when queried
//
// Note: needs 1 column at a minimum
$needs_eval_columns = ["rating_overall", "rating_overall_off", "offense_analysis"];



// ***************************
//
// File locations for all file directives in system
//
// Initial section specifies files used in Windows
//  and may also work for Linux.  Then comes a "Linux
//   Overide" section where variables can be overwritten 
//   if the system operates on a non-windows platform
//

// Default Files Locations

  // location of security files and routines

  $htpasswdfile = "../admin/htpasswd.master";
  $htpasswdexe = "../bin/htpasswd.exe";
  // $htpasswdexe = "C:\apps\Apache24\bin\htpasswd.exe";

  //
  // Schedule files and documentation
  //
  $schedule_xls = "sysfiles/qualification_schedule.xls";
  $schedule_csv = "sysfiles/qualification_schedule.csv";
  
  // 
  // log directory
  //
  $logdir = "log";
  
  //
  // auto-update file indicator
  //  If set to 1, turns on every 5 min updates from tBA
  //
  $auto_update_file = "sysfiles/autoupdate.ctl";
  //
  // end time each day for auto-update to turn off.  Specify as H:MMpm.
  $auto_update_stop = "7:30pm";
  
  //
  // Tablet Server Home directory
  //

  $tablet_server_home = "c:/tabletfiles/tabletserver";

//
// Linux File Location Overrides
if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') 
{
  
  //
  // Tablet Server Home directory
  //
  
  $tablet_server_home = "/home/roboload/TabletServer";

} 
// end of Linux file location overrides

//
// Tablet Server File Locations, based on $tablet_server_home
//

$tablet_templates = $tablet_server_home . "/templates";
$tablet_ingest_match = $tablet_server_home . "/ingest-match";
$tablet_ingest_pit = $tablet_server_home . "/ingest-pit";
$tablet_ingest_complete = $tablet_server_home . "/ingest-complete";
$tablet_ingest_error = $tablet_server_home . "/ingest-error";
$tablet_teamimages = $tablet_server_home . "/teamimages";
$tablet_export = $tablet_server_home . "/export";



// default user password (filled in)
$default_password = "wild4woodside";

// administrator users
$admins = array("jlv", "quentin", "abeaver", "akrantz");


//
// Message  timing for message recv application
//

$message_refresh = 6;


//
// Database parameters
//

$dbname = "competition";

$dbuser = "compuser";

$dbpass = "100hats";

$dbhost = "localhost";

// database collate character set map
//  Note: unfortunately, MySQL and MariaDB use case-insensitive collation
//   sequences by default for comparisons. Go figure.  For where clause
//   identification on keys, the "collate char-set" can be inserted behind
//   the column name to perform case-sensitive compares.  We use this structure
//   to change "where" clauses, for example in fields_insert
//
//   This parameter can be set to perform comparison.  Note that not all char sets
//   can be converted.  utf8mb4 will not convert to latin1_general_cs, as an example.
//
$db_case_sensitive = " collate utf8mb4_bin ";


// ***************************
//
// Misc App Configuration
//

// global default font
$font_sys_default = "arial";
//$font_sys_default = "helvetica";
  
// background colors used for red and blue teams
$background_colors = array ("R"=>"#ffaaaa", "Red"=>"#ffaaaa", "B"=>"#aaaaff", "Blue"=>"#aaaaff");

// background colors for other areas
$lyellow = '#ffff66';
$lgreen = '#ccffcc';
$lblue = '#ccccff';
$lred = '#ffcccc';

//length of the updated-by field.  Should not exceed db schema.  
//  Data will be truncated to this value
$maxlengthchangeby=200;

// display length of team name/number when it matters (index.php, rank, etc.)
$team_name_display_max = 40;

//
// time window (in seconds) for setting match start
//   if current time exceeds $schedule_set_match_window from the last estimated time, 
//   user will not be presented with "start now" option
//
$schedule_match_window = 1800;  // no more than an hour


// ***************************
//
// The Blue Alliance API mapping fields
//
// Field mappings to the API are defined in bluealliance.inc for performance reasons
//


// *****************************************************************
//
// Parameterized / Custom Functions
//
// Functions below are generally game-specific and must be written 
//  for each year
//

//
// Custom Rap Sheet Functions
//   $team array used in matchrapsheet
//      First three teams are "against" teams
//   $teamcnt - 5 or 6 depending if we are in alliance
//
// Custom Rap Sheet Allow insertion of analysis in three places in the Rap Sheet:
//   1. Before the Field Data comparison section
//   2. Before Competition Briefs
//   3. Before Cooperation Briefs
//
// Examples include data analysis that helps in competition, field configuration
//
// Functions should simply print to the web page.

// Inserted before Field Data
function rap_custom_field_data ($team, $teamcnt)
{
  global $connection;
  global $dispfields;
  
  print "\n<!--- Custom Insert before Field Data --->\n\n";
  
  // array to set PlayFields to use
  $defenses = [7,8,9,10,11,12,13,14,15];
    
  // build fields list
  $fields = "";
  foreach($defenses as $botfield)
  {
    if ($fields != "") $fields = $fields . ", ";
//DBCOL    $fields = $fields . "avg(PlayField_{$botfield}) PlayField_{$botfield} ";
    $fields .= "avg({$dispfields['Bot'][$botfield]['tag']}) {$dispfields['Bot'][$botfield]['tag']} ";
  }  
  
  // define and execute averages query
  
  $query = "select {$fields} from teambot where teamnum = {$team[0]['teamnum']}
             or teamnum = {$team[1]['teamnum']} or teamnum = {$team[2]['teamnum']}";
    
  if (debug()) print "<br>DEBUG-teamfinfosidebyside: " . $query . "<br>\n";
  if (! ($result = @ mysqli_query ($connection, $query)))
       dbshowerror($connection, "die");

  $row = mysqli_fetch_array($result);

  // load sort array
  $averages = array();
  foreach($defenses as $botfield)
//DBCOL    $averages = array_merge($averages, array(" {$botfield}" => $row["PlayField_{$botfield}"]));
//DBCOL -- ??? why space in array?
    $averages = array_merge($averages, array(" {$botfield}" => $row[$dispfields['Bot'][$botfield]['tag']]));

  // sort array
  asort($averages);

  // format data
  print "<br><b>Defense Selection</b> (lowest is most difficult for alliance)<br>\n";
  print "<table border=\"1\">\n";
  foreach ($averages as $stringfield=>$value)
  {
    $botfield = intval($stringfield);
    $displayvalue = sprintf("%.2f", $value);
    print "<tr><td>{$dispfields['Bot'][$botfield]['display']}</td><td>{$displayvalue}</td></tr>\n";
  }
  
  // end table 
  print "</table><br>\n";
    
  return;
}


// Inserted before Competition Briefs
function rap_custom_competition ($team, $teamcnt)
{
  global $connection;
  global $dispfields;
  
  print "\n<!--- Custom Insert before Competition Briefs --->\n\n";

  return;
}


// Inserted before Cooperation Briefs
function rap_custom_cooperation ($team, $teamcnt)
{
  global $connection;
  global $dispfields;
  
  print "\n<!--- Custom Insert before Cooperation Briefs --->\n\n";

  return;
}


//
// function that returns the seed score for a match
// give raw scores (startine with mine), penalty points, and other
function seedscore($my_raw_score, $their_raw_score, $my_penalties, $their_penalties, $my_other, $their_other)
{
   $my_score=$my_raw_score-$mypenalties;
   $their_score=$their_raw_score-$their_penalties;

   if($my_score>$their_score)
      $seed=$my_score+2*($their_score)+5;//winner's seed
   else if($my_score<$their_score)
      $seed=$their_raw_score;//looser's seed
   else
      $seed=3*$my_score;//tie
   return($seed);
}

//
// end of params.inc -- do not leave any lines under the end of this closure or html is not well-formed with top header line
//
?>