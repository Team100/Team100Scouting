<?php
  // $Revision: 2.1 $
  // $Date: 2010/04/22 04:00:55 $
  //
  // Competition System - Sample page with edits
  //
  require "page.inc";

  // get variables, checking for existance
  $teamnum=$_GET["teamnum"];
  if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;

  // header and setup
  pheader("Sample Edit - " . $teamnum);
  $connection = dbsetup();

  // initialize variables and arrays

  // define lock array, fields arrays
  //   the arrays set how array-based functions for lock and field editing work
  $dblock = array("table"=>"team","where"=>"teamnum = {$teamnum}");
  $table_team = array("name","nickname","org","location","students","website");

  // handle update if returning from edit mode
  if ($edit == 2)   // performs database save
  {
  	// load operation
  	if (isset($_POST["op"]) && ($_POST["op"] == "Save"))
	{
  		// check row
  		dblock($dblock,"check");

		// load form fields
		$formfields = fields_load("post", $table_team);
		$query = "update team set " . fields_insert("update",$formfields) . " where teamnum = {$teamnum}";

		// process query
		if (debug()) print "<br>DEBUG-template: " . $query . "<br>\n";
		if (! (@mysqli_query ($connection, $query) ))
			dbshowerror($connection, "die");

		// commit
		if (! (@mysqli_commit($connection) ))
			dbshowerror($connection, "die");
	}

	// abondon lock
	dblock($dblock,"abandon");

    // update completed, reset edit mode
    $edit = 0;
   }

   // lock tables if in edit mode
   if ($edit) dblock($dblock,"lock");  // lock row with current user id


//
// top of form rendering
//

print "
<!----- Top of page ----->
<table valign=\"top\">
<tr valign=\"top\">
<td>
"; // end of print

  //
  // create page
  //

  // check teamnum
  if (! ($teamnum)) print "<h1>No Team Number Specified</h1>\n";


  // get team details define result set
  $query = "select ". fields_insert("nameonly",NULL,$custom_param) . " from team where teamnum = {$teamnum}";
  if (debug()) print "<br>DEBUG-template: " . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // get row
  $row = mysqli_fetch_array($result);

  // field options
  $options["tr"] = 1;  // add tr tags

  // if in edit mode, signal save with edit=2
  if ($edit)
  	print "<form method=\"POST\" action=\"/teamdetails.php?edit=2&teamnum={$teamnum}\">\n";

  print "
  <!--- table for display data --->
  <table valign=\"top\">
  "
  . tabtextfield($edit,$options,$row, "name","Team name:",15,30)
  . tabtextfield($edit,$options,$row, "","",1,1)
  . "\n<tr><td><br><br></td></tr>\n<tr><td>\n";

  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/teamdetails.php?teamnum={$teamnum}") . "\n";

  // return and home buttons
  print "<br><br><a href=\"/teaminfo.php?teamnum={$teamnum}\">Return to Team Info</a><br>\n";
  print "<a href=\"/\">Return to Home</a>\n";

  // finish table and continue
  print "</td></tr></table>
  </td>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  <td>
  <table valign=\"top\">"
  . tabtextfield($edit,$options,$row, "location","Location: ",20,40)
  . "</table>
  </td>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  ";

  // close the form if in edit mode
  if ($edit) print "\n</form>\n";

  print "</tr>\n</table>\n";


?>

<?php
   pfooter();
  ?>
