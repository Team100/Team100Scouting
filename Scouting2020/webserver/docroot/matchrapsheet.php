<?php
  // $Revision: 3.0 $
  // $Date: 2016/03/14 22:56:41 $
  //
  // Competition System - Match Rap Sheet
  //
  // Shows competitive data for match
  //
  // Significant customization opportunities are available withing this form.
  //
  // To see the areas laid out on the form, turn the "$showareas flag to 1
  //  and rerender the form in your browser.
  //

  // uncomment the =1 assignment to show tags (for form design decisions)
  $showcustom = 0;
  // $showcustom = 1;

  require "page.inc";
  $connection = dbsetup();

  // initialize
  $edit=0;

  // load paramters

  // indicates "long form with all match listing on teams
  if (isset($_GET["long"])) $long=$_GET["long"]; else $long=NULL;

  // determine which version - public is sharable to other teams in alliance
  if (isset($_GET["public"]) && $_GET["public"] == "0") $public=0; else $public=1;

  // load variables
  $matchidentifiers = fields_load("GET", array("type", "matchnum"));
  $match_sql_identifier = "type = '{$matchidentifiers["type"]}'
		and matchnum = {$matchidentifiers["matchnum"]}";

  // set final flag
  if ($matchidentifiers["type"] == "F") $final=1; else $final="";

  // determine header
  $header = "{$matchidentifiers["type"]}-{$matchidentifiers["matchnum"]} - Match Rap Sheet";
  if (! ($public)) $header = $header . " (Private)";
  $header = $header . " - {$host_team_name}";
  pheader($header);


  // table data
  //

  // determine FIRST data columns
  // add stats columns to rankcolumns
  foreach($stats_columns as $statcolumn=>$statarray)
    $firstcols[] = $statcolumn;

  // add game-specific fields and stats columns
  foreach($dispfields["tBA_Bot"] as $rankfield)
    if ($rankfield['tag'] != NULL && $rankfield['used'] === TRUE)
      $firstcols[] = $rankfield['tag'];


  //  define columns for query
  $table_teambot = array_merge ( array("rank_overall","rating_overall","rating_overall_off","rating_overall_def",
  	"rank_pos1","rating_pos1","rank_pos2","rating_pos2","rank_pos3","rating_pos3","offense_analysis",
  	"defense_analysis","pos1_analysis","pos2_analysis","pos3_analysis", /* DEPRICATE? "robot_analysis",*/"driver_analysis",
  	"with_recommendation","against_recommendation"),
  	param_array("Bot"),
  	$firstcols
  	);

  // define rank fields
	$team_rank_fields = array("rank_overall"=>"Overall Rank", "rating_overall"=>"Overall Rating (0-9)",
		"rating_overall_off"=>"Offensive Rating (0-9)", "rating_overall_def"=>"Defensive Rating (0-9)");
	// if fields positions matter, add positions
	if ($field_positions === TRUE)
		$team_rank_fields = array_merge ( $team_rank_fields,
		  array ( "rank_pos1"=>"Position 1 Rank", "rating_pos1"=>"Position 1 Rating", "rank_pos2"=>"Position 2 Rank",
		  "rating_pos2"=>"Position 2 Rating", "rank_pos3"=>"Position 3 Rank", "rating_pos3"=>"Position 3 Rating" )
		);

	$eval_with_fields = array(
		"with_recommendation"=>"With Recommendation",
		"offense_analysis"=>"Offense Analysis",
		"defense_analysis"=>"Defense Analysis",
/* DEPRICATE?		"robot_analysis"=>"Overall Robot Analysis", */
		"driver_analysis"=>"Driver Analysis"
		);

    // if fields positions matter, add positions
	if ($field_positions === TRUE)
      $eval_with_fields = array_merge ( $eval_with_fields,
		array ( pos1_analysis=>"Position 1 Analysis",
		"pos2_analysis"=>"Position 2 Analysis",
		"pos3_analysis"=>"Position 3 Analysis")
		);

	$eval_against_fields = array(
		"against_recommendation"=>"Against Recommendation",
		"offense_analysis"=>"Offense Analysis",
		"defense_analysis"=>"Defense Analysis",
/* DEPRICATE?		"robot_analysis"=>"Overall Robot Analysis", */
		"driver_analysis"=>"Driver Analysis"
		);
	// if fields positions matter, add positions
	if ($field_positions === TRUE)
      $eval_against_fields = array_merge ( $eval_with_fields,
		array ( "pos1_analysis"=>"Position 1 Analysis",
		"pos2_analysis"=>"Position 2 Analysis",
		"pos3_analysis"=>"Position 3 Analysis")
		);

    // set query defaults
    $with_color="";
	$with_color_long="";
	$against_color="";
	$against_color_long="";
	$order="ASC";

	// get our team
	$query = "select teamnum, color from match_team where event_id = '{$sys_event_id}' and {$match_sql_identifier} and teamnum = {$host_teamnum}";
	if (debug()) print "<br>matchrapsheet: " . $query . "<br>\n";
	if (! ($result = @ mysqli_query ($connection, $query)))
		dbshowerror($connection, "die");

	$us = mysqli_fetch_array($result);

	// if us, set params
	if (isset($us))
  	  // determine which color
	  if ($us["color"] == "R")
	  {
		$with_color="R";
		$with_color_long="Red";
		$against_color="B";
		$against_color_long="Blue";
		$order="ASC";
	  }
	  else
	  {
		$with_color="B";
		$with_color_long="Blue";
		$against_color="R";
		$against_color_long="Red";
		$order="DESC";
	  }


    // get teams in match
    $query="select teambot.teamnum teamnum, match_team.color color, name, nickname nickname, location, org, bot_name, "
      . fields_insert("nameonly",NULL,$table_teambot)
      . " from match_team, teambot, team where match_team.event_id = '{$sys_event_id}' and teambot.event_id = '{$sys_event_id}' "
      . " and match_team.teamnum=teambot.teamnum and match_team.teamnum=team.teamnum"
      . " and match_team.teamnum != {$host_teamnum} and {$match_sql_identifier} order by match_team.color {$order}, match_team.teamnum";

	if (debug()) print "<br>DEBUG-matchrapsheet: " . $query . "<br>\n";
	if (! ($result = @ mysqli_query ($connection, $query)))
  		dbshowerror($connection, "die");

	// load teams and set team count for 5 or 6 teams as we load
  	$teamcnt=0;
  	while($row = mysqli_fetch_array($result))
		$team[$teamcnt++]=$row;

    // create default table header with teams
    $tablehead = "<th></th><th>{$against_color_long} {$team[0]["teamnum"]}</th>"
       . "<th>{$against_color_long} {$team[1]["teamnum"]}</th>"
       . "<th>{$against_color_long} {$team[2]["teamnum"]}</th>\n";

       // if public, don't include our alliance data
       if (! ($public))
       {
         $tablehead = $tablehead
         . "<th>{$with_color_long} {$team[3]["teamnum"]}</th>"
         . "<th>{$with_color_long} {$team[4]["teamnum"]}</th>";
         if ($teamcnt == 6) $tablehead = $tablehead . "<th>{$with_color_long} {$team[5]["teamnum"]}</th>";
       }


	// return home
	print "<a href=\"{$base}\">Return to Home</a>\n";
	print "&nbsp;&nbsp;&nbsp; <a href=\"/matchlist.php?final={$final}\">Match List</a>\n";

    //
	// time calculations to set up display
	//  - includes formatting scheduled time
	//  - feeding actual_utime to determine estimated time
	//  - if estimated and we can set, include a set button
	//      - processing for this case happens in the top of the page
	//
	// format scheduled time
    $query = "select scheduled_utime, actual_utime from match_instance where ".$match_sql_identifier;
    if (debug()) print "<br>matchrapsheet,time: " . $query . "<br>\n";
    if (! ($result = @ mysqli_query ($connection, $query) ))
      dbshowerror($connection, "die");
    $row = mysqli_fetch_array($result);

    if ($row['scheduled_utime'] != NULL) $scheduled_display = date('H:i',$row['scheduled_utime']); else $row['scheduled_utime'];
    // get publishing info on actual time / estimated time
    $time_array = match_get_act_est_time($matchidentifiers['type'], $matchidentifiers['matchnum'],$row['actual_utime'], $row['scheduled_utime']);

    print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	print "Scheduled: {$scheduled_display} &nbsp;&nbsp;&nbsp; {$time_array['heading_tag']} Time: {$time_array['display_time']}<br>\n";
	print "<br>";


    // format overall sheet and first table
    print "
    <!--- format over table --->
    <table valign=\"top\"><tr valign=\"top\"><td>\n

    <!--- format results table --->
    <table border=\"2\" valign=\"top\">
    <tr>{$tablehead}</tr>
    ";

	// loop through data in first fields and populate
	foreach ( $team_rank_fields as $fieldname => $field_desc)
	{

		// start row
		print "<tr><td>{$field_desc}</td>\n";

		// loop through teams
		// if public, set to 3 (competition) vs $teamcnt for all
		if ($public) $tot=3; else $tot=$teamcnt;
		for($i=0; $i<$tot; $i++)
			print "<td>{$team[$i][$fieldname]}</td>";

	 	//end row
		print "</tr>\n";
	}
	// end data table
	print "</table></td>\n";

	// end of first row of data tables
    print "</tr><tr valign=\"top\"><td>\n";

	// loop through field and custom data

    // call custom function before field data (show place if $showcustom =1)
    if ($showcustom) print "\n--- Custom area after summary block ---<br>\n";
    rap_custom_post_summary($team, $teamcnt);


    // format overall sheet and first table
    print "

    <!--- format over table - field data --->
    <b>Field Data</b><br>
    ";

    // (show place if $showcustom =1)
    if ($showcustom) print "\n--- Custom area before field data ---<br>\n";
    rap_custom_pre_field_data($team, $teamcnt);

    print "
    <table valign=\"top\"><tr><td>\n

    <!--- format results table --->
    <table border=\"2\" valign=\"top\">
    <tr>{$tablehead}</tr>
    ";

    //
    // parameters side-by-side
    //
    print param_report ($team, "Bot", $public, $teamcnt);

    // rank and stats side by side
    print "<tr></tr>\n";
    print "<tr></tr>\n";
    print "<tr></tr>\n";

    // set number of columns
    if ($public) $colcnt=3; else $colcnt = $teamcnt;

    // add stats columns and game-specific fields
    //
    // stats
    foreach($stats_columns as $column=>$col_def)
    {
      print "<tr><td>{$col_def['display']}</td>";
      // data in each
      for($i=0; $i<$colcnt; $i++)
      {
        // check for format
        if ($col_def['format'] != "")
          $show = sprintf($col_def['format'], $team[$i][$column]);
        else
          $show = $team[$i][$column];
        print "<td>{$show}</td>";
      }
      print "</tr>\n";
    }


    $rankcolumns = "";   // initialize
    foreach($dispfields["tBA_Bot"] as $rankfield)
      if ($rankfield['tag'] != NULL && $rankfield['used'] === TRUE)
      {
        print "<tr><td>{$rankfield['display']}</td>\n";

        // data in each
        for($i=0; $i<$colcnt; $i++)
        {
          if (isset($rankfield['format']) && ($rankfield['format'] != ""))
            $value = sprintf($rankfield['format'], $team[$i][$rankfield['tag']]);
          else
            $value = $team[$i][$rankfield['tag']];
          print "<td>{$value}</td>";
        }
        print "</tr>\n";
      }



    // end of display table
	print "</table>\n";

	// end of format table
	print "</td></tr></table>\n";

	// end of another format table
    print "</tr></table>\n";

    // (show place if $showcustom =1)
    if ($showcustom) print "\n--- Custom area after field data ---<br>\n";
    rap_custom_post_field_data($team, $teamcnt);

    //
    // print narrative comparatives
    //

	// for competition first
	print "<hr>\n";
	print "<h2>Competition Briefs</h2>\n";

    // call custom function before competition briefs (show place if $showcustom =1)
    if ($showcustom) print "\n--- Custom area before competition data ---<br>\n";
    rap_custom_pre_competition($team, $teamcnt);

    // loop through teams
    for($i=0; $i<3; $i++)
    {
        // team heading
        print "<tr><td><h3>Team {$team[$i]["teamnum"]} - {$team[$i]["name"]}";
        // if nickname, print too
        if ($team[$i]["nickname"]) print " ({$team[$i]["nickname"]})";
        print "</h3>\n";
        print "<b>Location:</b>{$team[$i]["location"]} &nbsp;&nbsp;&nbsp; \n";
        if(isset($team[$i]["org"]))
          print "<b>Org:</b>{$team[$i]["org"]} &nbsp;&nbsp;&nbsp; \n";
        if(isset($team[$i]["bot_name"]))
          print "<b>Robot Name:</b>{$team[$i]["bot_name"]} &nbsp;&nbsp;&nbsp; \n";
        print "<br>\n";
        print "<table border=\"2\" valign=\"top\">";

        // loop through data in first fields and populate
	    foreach ( $eval_against_fields as $fieldname => $field_desc)
	   		print "<tr valign=\"top\"><td>{$field_desc}</td><td>{$team[$i][$fieldname]}</td></tr>\n";

		// finish table
		print "</table>\n";
	}

    // call custom function before competition briefs (show place if $showcustom =1)
    if ($showcustom) print "\n--- Custom area after competition data ---<br>\n";
    rap_custom_post_competition($team, $teamcnt);

	// partner alliance next -- only if not public
	if (! ($public))
	{
		print "<hr>\n";
		print "<h2>Cooperation Briefs</h2>\n";

        // call custom function before cooperation briefs (show place if $showcustom =1)
        if ($showcustom) print "\n--- Custom area before cooperation brief ---<br>\n";
        rap_custom_pre_cooperation($team, $teamcnt);


        // loop through other teams  (3 or teamcnt)
		for($i=3; $i<$teamcnt; $i++)
		{
			// team heading
			print "<tr><td><h3>Team {$team[$i]["teamnum"]} - {$team[$i]["name"]}";
			// if nickname, print too
			if ($team[$i]["nickname"]) print " ({$team[$i]["nickname"]})";

			//print "</h3>\n<table border=\"2\" valign=\"top\">";
            print "</h3>\n";
            print "<b>Location:</b>{$team[$i]["location"]} &nbsp;&nbsp;&nbsp; \n";
            if(isset($team[$i]["org"]))
              print "<b>Org:</b>{$team[$i]["org"]} &nbsp;&nbsp;&nbsp; \n";
            if(isset($team[$i]["bot_name"]))
              print "<b>Robot Name:</b>{$team[$i]["bot_name"]} &nbsp;&nbsp;&nbsp; \n";
            print "<br>\n";
            print "<table border=\"2\" valign=\"top\">";

			// loop through data in first fields and populate
			foreach ( $eval_with_fields as $fieldname => $field_desc)
				print "<tr valign=\"top\"><td>{$field_desc}</td><td>{$team[$i][$fieldname]}</td></tr>\n";

			// finish table
			print "</table>\n";
		}

        // call custom function before cooperation briefs (show place if $showcustom =1)
        if ($showcustom) print "\n--- Custom area before cooperation brief ---<br>\n";
        rap_custom_post_cooperation($team, $teamcnt);

	}

?>


<?php
  //
  // *************************************************************************
  //
  // Added team match evaluation data on the end of each match
  //
  // Code essentially copied from teammatches.php
  //


// if long form
if ($long)
{

  require "fieldnames.inc";

	// if fields positions matter, start with position
	if ($field_positions === TRUE)
		$field_array = array("position");
	else
		$field_array = array();

	// define fields used
	$field_array = array_merge ($field_array, array("rating_offense", "rating_defense", "raw_points",
		"penalties","match_offense_analysis", "match_defense_analysis","match_pos_analysis",
		"match_with_recommendation","match_against_recommendation"),
  		param_array("Match"));

  // print large spacer
  print "
  <b><hr width=\"200%\"></b>
  <b><hr width=\"200%\"></b>
  <h1>Match details for each team</h1>
  ";  // end of print

//
// each team
//
//
//  for each team, print out all matches

// if public, only print first 3, not competition
if ($public) $tot=3; else $tot=$teamcnt;
for($i=0; $i<$tot; $i++)
 {
  // set team, and let it go from there
  $teamnum=$team[$i]["teamnum"];

  // announce team
  print "<hr width=\"200%\">\n";
  print "<hr width=\"200%\">\n";

  // team heading
  print "<h2>Team {$team[$i]["teamnum"]} - {$team[$i]["name"]}";
  // if nickname, print too
  if ($team[$i]["nickname"]) print " ({$team[$i]["nickname"]})";
  print "</h2>\n";



 //
 // top of loop
 //

 $query =
     "select type, matchnum from match_team where event_id = '{$sys_event_id}' and teamnum = {$teamnum} order by type, matchnum";

 if (debug()) print "<br>matchrapsheet: " . $query . $where . "<br>\n";
 if (! ($matches_result = @ mysqli_query ($connection, $query) ))
		dbshowerror($connection, "die");

 while ($matches_row = mysqli_fetch_array($matches_result) )
 {
  $matchidentifiers = array ("type"=>$matches_row["type"],
    	"matchnum"=>$matches_row["matchnum"]);
  //$type = $matches_row["type"];
  //$matchnum = $matches_row["matchnum"];


  // set up variables for this run
	// $matchidentifiers = fields_load("GET", array("event_id", "type", "matchnum", "teamnum"));

	$match_sql_identifier =
		"event_id = '{$sys_event_id}' and type = '{$matchidentifiers["type"]}'
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

  $query = "select type, matchnum, scheduled_utime, actual_utime
	from match_instance where ".$match_sql_identifier;

   if (debug()) print "<br>matchrapsheet: " . $query . "<br>\n";
   if (! ($result = @ mysqli_query ($connection, $query) ))
     dbshowerror($connection, "die");
   if (! ($resultR = @ mysqli_query ($connection, "select score from match_instance_alliance where {$match_sql_identifier} and color='R'") ))
     dbshowerror($connection, "die");
   if (! ($resultB = @ mysqli_query ($connection, "select score from match_instance_alliance where {$match_sql_identifier} and color='B'") ))
     dbshowerror($connection, "die");

   $row = mysqli_fetch_array($result);
   $pointsR = mysqli_fetch_array($resultR);
   $pointsB = mysqli_fetch_array($resultB);

   //print match data
   print "<tr><th>Type</th><th>Match</th><th>Sched</th><th>Actual</th><th>Red</th><th>Blue</th></tr>";
   print "<tr><td>".$row["type"]."</td><td>".$row["matchnum"]."</td>";

   // format scheduled time
   if ($row['scheduled_utime'] != NULL) $scheduled_display = date('H:i',$row['scheduled_utime']);
     else $scheduled_display = $row['scheduled_utime'];
   if ($row['actual_utime'] != NULL) $actual_display = date('H:i',$row['actual_utime']);
     else $actual_display = $row['actual_utime'];

   print "<td>{$scheduled_display}</td><td>{$actual_display}</td><td>{$pointsR['score']}</td><td>{$pointsB['score']}</td></tr>\n";

   //print teams
   $color_names = array("R"=>"Red", "B"=>"Blue");
   print "<table border=1><tr><b>Teams:</b></tr><tr>";//<td>Red</td>;

   foreach(array('R', 'B') as $color_initial)
   {
     print "<td>{$color_names[$color_initial]}</td>";
     if (debug()) print "<br>matchrapsheet: " . $query . "<br>\n";
     if (! ($result = @ mysqli_query ($connection, "select teamnum from match_team where ".$match_sql_identifier." and color='{$color_initial}'") ))
         dbshowerror($connection, "die");
     while($row = mysqli_fetch_array($result))
     {
       if($row["teamnum"]==$teamnum)
         print "<td>{$row["teamnum"]}</td>";
       else
         print "<td>{<a href=\"/matchteameval.php?teamnum={$row["teamnum"]}&"
     		. "type={$matchidentifiers["type"]}&matchnum={$matchidentifiers["matchnum"]}\">{$row["teamnum"]}</a></td>";
	 }
     print"</tr><tr>";
   }
   print "</tr></table>";

   print "
   <!---Individual Team Evaluation--->
   ";

   $query = "select ". fields_insert("nameonly",NULL,$field_array)
 	. " from match_team where {$match_sql_identifier} and {$team_sql_identifier}";

	if (debug()) print "<br>matchrapsheet: " . $query ."<br>\n";
	if (! ($result = @ mysqli_query ($connection, $query)))
		dbshowerror($connection, "die");
	$row = mysqli_fetch_array($result);

	// page break
	print "</td><td>";

	// start table
	print "<table border=2>";

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
	  <b>Match-specific Variables:</b>
	  <!--- layout table --->
	  <table><tr valign=\"top\"><td>

	  <table border=\"1\" valign=\"top\">
	   ";  // end of print

	  // get play variables
	  $options["tr"]=TRUE;
	  $options["notag"]=FALSE;
	  $options["pagebreak"]=2;
	  $options["pagebreakstring"]="\n</table>\n</td><td>\n<table border=\"1\" valign=\"top\">";
	  print tabparamfields($edit, $options, $row, "Match");

  // end blocks of data, table layout
  print "\n</table>
    </td></tr></table>
    </td></tr></table>";

	//
	// full text field input
	//
// analysis table format
  $options["notag"]=FALSE;
  print "<table>
  <tr>
  <td>";

  // print out variables through table print
  print tabtextarea($edit,$options,$row, "match_notes","Notes and additions specific to this match:",8,100);

  // close table
  print "</td></tr></table></table>\n";


 // end of multi match loop
 }

 // team for loop
 }
// end of long
}


// return home
print "<br><br>\n";
print "<a href=\"{$base}\">Return to Home</a>\n";
print "&nbsp;&nbsp;&nbsp; <a href=\"/matchlist.php?final={$final}\">Match List</a>\n";

	pfooter();
?>