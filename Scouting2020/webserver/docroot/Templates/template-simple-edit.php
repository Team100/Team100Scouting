<?php
  // $Revision: 2.1 $
  // $Date: 2010/04/22 04:00:55 $
  //
  // Competition System - Sample page with edits
  //
  require "page.inc";
  $pagename = "template-simpe-edit";

  // get variables, checking for existance
  if(isset($_GET["vargroup"])) $vargroup=$_GET["vargroup"]; else $vargroup="Bot";
  if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;

  // header and setup
  pheader("Custom Parameters Setup - Be Careful");
  $connection = dbsetup();

  // initialize variables and arrays

  // define lock array, fields arrays
  //   the arrays set how array-based functions for lock and field editing work
  $dblock = array("table"=>"custom_param","where"=>"vargroup = '{$vargroup}'");
  $custom_param = array("tag","position","used","vargroup","entrytype","dbtype","display","inputlen","maxlen",
     "default_value","list_of_values","db_calc","formula_calc","test_avg","test_range","test_values");

  // handle update if returning from edit mode
  if ($edit == 2)   // performs database save
  {
  	// load operation
  	if (isset($_POST["op"]) && ($_POST["op"] == "Save"))
	{
  		// check row
  		dblock($dblock,"check");

		// load form fields
		$formfields = fields_load("post", $custom_param);
		$query = "update custom_param set " . fields_insert("update",$formfields) . " where tag = {$tag}";

		// process query
		if (debug()) print "<br>DEBUG-{$pagename}: " . $query . "<br>\n";
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

  // get custom_param details define result set
  $query = "select ". fields_insert("nameonly",NULL,$custom_param) . " from custom_param where vargroup = '{$vargroup}'";
  if (debug()) print "<br>DEBUG-template: " . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // get row
  $row = mysqli_fetch_array($result);

  // field options
  $options["tr"] = 1;  // add tr tags

  // if in edit mode, signal save with edit=2
  if ($edit)
  	print "<form method=\"POST\" action=\"/{$pagename}.php?edit=2\">\n";

  // Use tabtextfield($edit, $options, $data, $fieldname, $fieldtag, $size, $maxlenth, $defvalue)
  // for each field
  print "
  <!--- table for display data --->
  <table valign=\"top\">
  "
  . tabtextfield($edit,$options,$row, "tag","Tag",10,15)
  . tabtextfield($edit,$options,$row, "position","Ord",3,3)
  . tabtextfield($edit,$options,$row, "used","Used",1,1)
  . tabtextfield($edit,$options,$row, "entrytype","EntTyp",1,1)
  . tabtextfield($edit,$options,$row, "dbtype","DB type",8,8)
  . tabtextfield($edit,$options,$row, "display","Display",14,15)
  . tabtextfield($edit,$options,$row, "inputlen","InpLen",2,2)
  . tabtextfield($edit,$options,$row, "maxlen","MaxLen",2,2)
  . tabtextfield($edit,$options,$row, "default_value","Def Val",10,20)
  . tabtextfield($edit,$options,$row, "list_of_values","List of Val",10,100)
  . tabtextfield($edit,$options,$row, "db_calc","DB Calc",10,50)
  . tabtextfield($edit,$options,$row, "formula_calc","Formula Calc",10,200)
  . tabtextfield($edit,$options,$row, "test_avg","TestAvg",3,3)
  . tabtextfield($edit,$options,$row, "test_range","TestRng",3,3)
  . tabtextfield($edit,$options,$row, "test_values","TestValues",10,200)
  . "\n<tr><td><br><br></td></tr>\n<tr><td>\n";

  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/{$pagename}.php?vargroup={$vargroup}") . "\n";

  // return and home buttons
  print "<br><br><a href=\"/admin.php\">Return to Admin</a><br>\n";
  print "<a href=\"/\">Return to Home</a>\n";

  // finish table and continue
  print "</td></tr></table>\n";


  // close the form if in edit mode
  if ($edit) print "\n</form>\n";

  print "</tr>\n</table>\n";

   pfooter();
  ?>