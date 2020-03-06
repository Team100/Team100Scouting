<?php
  // $Revision: 2.1 $
  // $Date: 2010/04/22 04:00:55 $
  //
  // Competition System - Sample page with edits
  //
  require "page.inc";

  // get variables, checking for existance
  if(isset($_GET["vargroup"])) $vargroup=$_GET["vargroup"]; else $vargroup="Bot";
  if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;

  // header and setup
  pheader("Custom Parameters Setup - Group: {$vargroup}");
  $connection = dbsetup();

  // initialize variables and arrays

  // define lock array, fields arrays
  //   the arrays set how array-based functions for lock and field editing work
  $custom_param = array("tag","position","used","vargroup","entrytype","dbtype","display","inputlen","maxlen",
     "default_value","list_of_values","db_calc","formula_calc","test_avg","test_range","test_values");

  // because we're dealing with multiple rows, use a process lock
  $dblock = array("table"=>"process_lock","where"=>"lock_id = 'custom_param'");

  // handle update if returning from edit mode
  if ($edit == 2)   // performs database save
  {
  	// load operation
  	if (isset($_POST["op"]) && ($_POST["op"] == "Save"))
	{
  		// check row
  		dblock($dblock,"check");

        // loop through all fields, looking for non-null tags and process

		// load form fields
		for ($cnt = 0; $cnt < $custom_params_limit; $cnt++)
		{
		  $formfields = fields_load("post", $custom_param, $cnt . "__");
		  //$query = "update custom_param set " . fields_insert("update",$formfields) . " where tag is not null";

  		  // process query
		  //if (debug()) print "<br>DEBUG-customparam: " . $query . "<br>\n";
		  //if (! (@mysqli_query ($connection, $query) ))
		  //	dbshowerror($connection, "die");


		  // if not null, create or insert

		  if ($formfields["tag"] != NULL )
		  {
		    // add vargroup
		    $formfields["vargroup"] = $vargroup;
		    // specify where
		    $where = array( "vargroup"=>'{$vargroup}', "tag"=>'$formfields["tag"]');
		    db_update_or_create("custom_param", $where, $formfields);
		  }

		}

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

  print "\n\n";
  // if in edit mode, signal save with edit=2
  if ($edit)
  	print "<form method=\"POST\" action=\"/customparam.php?edit=2\">\n";


  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/customparam.php?vargroup={$vargroup}") . "\n";

  // return and home buttons
  print "&nbsp;&nbsp;&nbsp;<a href=\"/admin.php\">Return to Admin</a>\n";
  print "&nbsp;&nbsp;&nbsp;<a href=\"/\">Return to Home</a><br><br>\n";

print "
<!----- Top of page ----->
<table valign=\"top\" border=\"2\">
"; // end of print

  //
  // create page
  //

  // inline function to render page rows
  function custom_render_row ($edit, $options, $row, $editprefix=NULL)
  {
    // Use tabtextfield($edit, $options, $data, $fieldname, $fieldtag, $size, $maxlenth, $defvalue, $editprefix)
    // for each field

    print "<tr align=\"left\">\n"
    . tabtextfield($edit,$options,$row, "tag","Tag",10,15,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "position","Pos",3,3,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "used","Used",1,1,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "entrytype","EntTyp",1,1,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "dbtype","DB type",8,8,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "display","Display",14,15,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "inputlen","InpLen",2,2,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "maxlen","MaxLen",2,2,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "default_value","Def Val",10,20,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "list_of_values","List of Val",10,100,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "db_calc","DB Calc",10,50,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "formula_calc","Formula Calc",10,200,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "test_avg","TestAvg",3,3,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "test_range","TestRng",3,3,NULL,$editprefix)
    . tabtextfield($edit,$options,$row, "test_values","TestValues",10,200,NULL,$editprefix)
    . "</tr>\n\n";
  }

  // render header
  // field options
  $options["header"] = TRUE;  // add header
  $options["th"] = TRUE;  // add header
  $row = array();
  custom_render_row ($edit, $options, $row);

  // get custom_param details define result set
  $query = "select ". fields_insert("nameonly",NULL,$custom_param) . " from custom_param where vargroup = '{$vargroup}'";
  $query = $query . " order by position";
  if (debug()) print "<br>DEBUG-template: " . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // set options
  $options["header"] = FALSE;  // remove header
  $options["th"] = FALSE;  // add header
  $options["notag"] = TRUE;  // add header

  // counter to track number of rows
  $cnt = 0;

  // get rows and layout form
  while($row = mysqli_fetch_array($result))
  {
    custom_render_row ($edit, $options, $row);
    $cnt++;
  }

  // finish off form to end of data if in edit mode
  if ($edit)
  	while($cnt < $custom_params_limit)
    {
      custom_render_row ($edit, $options, $row, $cnt . "__");
      $cnt++;
    }

  // end table
  print "</table><br>\n";


  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/customparam.php?vargroup={$vargroup}") . "\n";

  // return and home buttons
  print "<br><br><a href=\"/admin.php\">Return to Admin</a><br>\n";
  print "<a href=\"/\">Return to Home</a>\n";


  // close the form if in edit mode
  if ($edit) print "\n</form>\n";

  // print "</tr>\n</table>\n";


   pfooter();
  ?>