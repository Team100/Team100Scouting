<?php
  // $Revision: 3.0 $
  // $Date: 2016/03/14 22:56:41 $
  //
  // Competition System - Show Team Matches info for all matches
  //
  require "page.inc";
  require "fieldnames.inc";

  // get variables
  $teamnum=$_GET["teamnum"];
  if (isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit = NULL;

  // setup
  $connection = dbsetup();

  // header and setup
  pheader($teamnum . " - Team Matches Listing", "titleonly");

  // check team number
  if (! ($teamnum)) showerror("<h1>No Team Number Specified</h1>","die");

  // define fields used
  $field_array = array_merge (array("position","rating_offense", "rating_defense", "raw_points",
	"penalties","match_offense_analysis", "match_defense_analysis","match_pos_analysis",
	"match_with_recommendation","match_against_recommendation"),
	param_array("Match"));

  //
  // print top of page
  //

  // get basic teaminfo details define result set
  if (!($result = @ mysqli_query ($connection,
  	"select name, nickname from team where teamnum = {$teamnum}")))
    dbshowerror($connection);
  // get row
  $row = mysqli_fetch_array($result);

  // print team number, name and nickname as page header
  print "<a href=\"/teaminfo.php?teamnum={$teamnum}\"><H2>Match Listings - " . team_needs_eval_str($teamnum)
         . " - {$row["name"]}</a>";
  if ($row["nickname"]) print "({$row["nickname"]})";
  print "</H2>\n";


  // return and home buttons
  print "<a href=\"/teaminfo.php?teamnum={$teamnum}\">Return to Team Info</a><br>\n";
  print "<a href=\"{$base}\">Return to Home</a>\n";

 //
 // top of loop
 //

 $query =
     "select event_id, type, matchnum from match_team where teamnum = {$teamnum} order by type, matchnum";

 if (debug()) print "<br>DEBUG-teammatches: " . $query . "<br>\n";
 if (! ($matches_result = @ mysqli_query ($connection, $query) ))
		dbshowerror($connection, "die");

 while ($matches_row = mysqli_fetch_array($matches_result) )
 {
   $matchidentifiers = array ("event_id"=>$matches_row["event_id"], "type"=>$matches_row["type"],
    	"matchnum"=>$matches_row["matchnum"]);
   //$event_id = $matches_row["event_id"];
   //$type = $matches_row["type"];
   //$matchnum = $matches_row["matchnum"];


   // set up variables for this run
   // $matchidentifiers = fields_load("GET", array("event_id", "type", "matchnum", "teamnum"));

   $match_sql_identifier =
		"event_id = '{$matchidentifiers["event_id"]}' and type = '{$matchidentifiers["type"]}'
		and matchnum = {$matchidentifiers["matchnum"]}";
   $team_sql_identifier = "teamnum={$teamnum}";


   // top of match listing
   print "<hr>\n";

   print "
	<!---- Table layout ---->
	<table valign=\"top\">
	<tr valign=\"top\"><td>

	<table valign=\"top\">
	<tr valign=\"top\"><td>

	<!---General Match Info Display--->
	<table valign=\"top\" border=1>
   ";

   // event_id

   $query = "select event_id, type, matchnum, scheduled_utime, actual_utime
    	from match_instance where ".$match_sql_identifier;

   if (debug()) print "<br>DEBUG-teammatches: " . $query . "<br>\n";
   if (! ($result = @ mysqli_query ($connection, $query) ))
		dbshowerror($connection, "die");

   $queryR = "select score from match_instance_alliance where {$match_sql_identifier} and color='R'";
   if (debug()) print "<br>DEBUG-teammatches: " . $queryR . "<br>\n";
   if (! ($resultR = @ mysqli_query ($connection, $queryR) ))
    	dbshowerror($connection, "die");

   $queryB = "select score from match_instance_alliance where {$match_sql_identifier} and color='B'";
   if (debug()) print "<br>DEBUG-teammatches: " . $queryB	 . "<br>\n";
   if (! ($resultB = @ mysqli_query ($connection, $queryB) ))
		dbshowerror($connection, "die");

   $row = mysqli_fetch_array($result);
   $pointsR = mysqli_fetch_array($resultR);
   $pointsB = mysqli_fetch_array($resultB);

   //
   // set up  time
   //
   if ($row['scheduled_utime'] != NULL) $scheduled_display = date('H:i',$row['scheduled_utime']); else $row['scheduled_utime'];
   // get publishing info on actual time / estimated time
   $time_array = match_get_act_est_time($row["type"], $row["matchnum"], $row['actual_utime'], $row['scheduled_utime']);
   // end of time

   //print match data
   print "<tr><th>Event</th><th>Type</th><th>Match</th><th>Sched</th><th>{$time_array['heading_tag']}</th><th>Red</th><th>Blue</th></tr>\n";
   print "<tr><td>".$row["event_id"]."</td><td>".$row["type"]."</td><td>".$row["matchnum"]."</td>\n";

   // display time
   print "<td>{$scheduled_display}</td><td>{$time_array['display_time']}</td>";

   print "<td>".$pointsR["score"]."</td><td>".$pointsB["score"]."</td></tr>\n";


   //print teams
   $color_names = array("R"=>"Red", "B"=>"Blue");
   print "<table border=1><tr><b>Teams:</b></tr><tr>";//<td>Red</td>\n;

   foreach(array('R', 'B') as $color_initial)
   {
     print "<td>{$color_names[$color_initial]}</td>";
     if (! ($result = @ mysqli_query ($connection, "select teamnum from match_team where ".$match_sql_identifier." and color='{$color_initial}'") ))
        dbshowerror($connection, "die");
 /*    while($row = mysqli_fetch_array($result))
     {
       if($row["teamnum"]==$teamnum)
         print "<td>{$row["color"]} {$row["teamnum"]}</td>\n";
       else
         print "<td>{$row["color"]} <a href=\"/matchteameval.php?teamnum={$row["teamnum"]}&event_id={$matchidentifiers["event_id"]}&
          type={$matchidentifiers["type"]}&matchnum={$matchidentifiers["matchnum"]}\">{$row["teamnum"]}</a></td>\n";
     }
*/
    while($row = mysqli_fetch_array($result))
     {
       if($row["teamnum"]==$teamnum)
         print "<td>{$row["teamnum"]}</td>\n";
       else
         print "<td><a href=\"/matchteameval.php?teamnum={$row["teamnum"]}&event_id={$matchidentifiers["event_id"]}&
          type={$matchidentifiers["type"]}&matchnum={$matchidentifiers["matchnum"]}\">{$row["teamnum"]}</a></td>\n";
     }
     print"</tr><tr>";
   }
   print "</tr></table>";

   print "
    <br>

	<!---Individual Team Evaluation--->
   ";

   $query = "select ". fields_insert("nameonly",NULL,$field_array)
  		. ", match_notes from match_team where {$match_sql_identifier} and {$team_sql_identifier}";

	if (! ($result = @ mysqli_query ($connection, $query)))
		dbshowerror($connection, "die");
	$row = mysqli_fetch_array($result);

	// start table
	print "<table border=2>\n";
	//<tr><td>Color:</td><td>".$row["color"]."</td></tr>";

	$options["tr"] = 1;  // add tr tags
	print fill_tab_text_field($edit, $options, $row, $match_team_name, $match_team_size);

	print "</table>";

	print "
	<!--- table layout to other column --->
	</td><td>&nbsp;&nbsp;</td><td>
	";


	//
	// Match-specific fields
	//

	  // Per match variables
	  print "
	  <b>Match-Specific Variables</b>
	  <table><tr valign=\"top\"><td>

	  <table border=\"1\" valign=\"top\">";

	  // get play variables
	  $options["tr"]=TRUE;
	  $options["notag"]=FALSE;
	  $options["pagebreak"]=2;
	  $options["pagebreakstring"]="</table></td><td><table border=\"1\">\n";
	  print tabparamfields($edit, $options, $row, "Match");

  // end blocks of data, layout
  print "\n</table>
  	</td></tr></table>
  	</td></tr></table>
  	";// end of print


	//
	// full text field input
	//
// analysis table format
  $options["notag"]=FALSE;
  print "<table>
  <tr>
  <td>";

  print tabtextarea($edit,$options,$row,"match_notes","Notes and additions specific to this match:",8,100);

  //print tabtextarea($edit,$options,$row, "match_offense_analysis","Offensive Analysis:",4,100)
 // . tabtextarea($edit,$options,$row, "match_defense_analysis","Defensive Analysis:",4,100)
//  . tabtextarea($edit,$options,$row, "match_pos_analysis","Position Analysis:",4,100)
//  . tabtextarea($edit,$options,$row, "match_with_recommendation","With Recommendation:",4,100)
//  . tabtextarea($edit,$options,$row, "match_against_recommendation","Against Recommendation:",4,100);
//

  // close table
  print "</td></tr></table>\n";


 // end of multi match loop
 }



	pfooter();
?>