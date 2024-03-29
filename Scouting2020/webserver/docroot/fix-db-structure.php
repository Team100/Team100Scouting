<?php
  // $Revision: 3.0 $
  // $Date: 2016/03/14 22:56:41 $
  //
  // Competition System - Fix database structure page
  //
  // The notion of this routine is a consistency check for various data errors that are
  //   discovered in the course of use of the system.

  require "page.inc";
  // get variables

  pheader("Inspect and Fix Database Structure");
  $connection = dbsetup();

  // return home
  print "<a href=\"{$base}\">Return to Home</a>\n";
  print "&nbsp;&nbsp;&nbsp; <a href=\"/admin.php\">Sys Admin</a><br>\n";

 // if not administrator, display error.  Otherwise show admin section.
 if (! $admin)
   print "<h3>You must be an administrator to use this page.</h3>\n";
 else
 {

  print "
    <br>
  	This function tests database structure, reports errors, and fixes what is possible to fix.
  	<p>
  	<b>Please read carefully through results and verify they match what is expected from the data.</b>
  	<p>
  	<hr>
  	\n";


  // teambot and team structure
  print "<h4>Checking all competition teams are loaded and repairing if needed</h4>\n";

  // query for teams in teambot and not in teams
  $query =
       "select teamnum from teambot where teamnum not in (select teamnum from team)";

   if (! ($matches_result = @ mysqli_query ($connection, $query) ))
  		dbshowerror($connection, "die");

   // set loop counter.  If 0, all teams were in db.
   $loopcnt = 0;
   while ($row = mysqli_fetch_array($matches_result) )
   {
   		// if starting loop, show issue
   		if ($loopcnt == 0)
   			print "Warning! The following teams are listed in teambot table but not in the teams table.
   			   These teams are probably new:<br>\n";

   		// list team
   		print "&nbsp; &nbsp; &nbsp; &nbsp; ". $row['teamnum'] . "<br>\n";
   		$loopcnt = $loopcnt+1;
   }

   // if no problems with teams were found, report no problem to user.  Otherwise, start repair.
   if ($loopcnt == 0)
   {
   	   	print "No problems with teams found.  All teambot teams are found in teams table.<br>\n";
   }
   else
   {
   		// fix team
   		print "<br>Repairing teams table...";
   		$query =
		   "insert into team (teamnum) select teamnum from teambot where teamnum not in (select teamnum from team)";

		   if (! ($matches_result = @mysqli_query ($connection, $query) ))
		  		dbshowerror($connection, "die");

		   print "done.<br>\n";

  		//
  		// commit transation
  		print "Commiting transactions...";

  		if (! (@mysqli_commit($connection) ))
  		  dbshowerror($connection, "die");

		print "done.<br>\n";

   }


 } // end of "if admin" qualification


   // return home
   print "<br><br><a href=\"{$base}\">Return to Home</a>\n";
   if ($admin) print "&nbsp;&nbsp;&nbsp; <a href=\"/admin.php\">Sys Admin</a>\n";
   print "<br>\n";

   pfooter();
 ?>
