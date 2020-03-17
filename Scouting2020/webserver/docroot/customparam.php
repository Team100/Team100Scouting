<?php
// $Revision: 2.1 $
// $Date: 2010/04/22 04:00:55 $
//
// Competition System - Custom Parameter Setup
//
// Note: the Blue Alliance parameters use the same setup page but have special
//        handling.  vargroup=tBA
//
require "page.inc";
$pagename = "customparam";

// get variables, checking for existance
if(isset($_GET["vargroup"])) $vargroup=$_GET["vargroup"]; else $vargroup="Bot";
if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;

// header and setup
pheader("Custom Parameters Setup - Group: {$vargroup}");
$connection = dbsetup();

// if not administrator, display error.  Otherwise show admin section.
if (! $admin)
  print "<h3>You must be an administrator to use this page.</h3>\n";
else
{
  // initialize variables and arrays
  $dbtypes = array("varchar","int","real","boolean");

  // define lock array, fields arrays
  //   the arrays set how array-based functions for lock and field editing work
  $custom_param = array("tag","position","used","vargroup","entrytype","dbtype","display","heading",
     "inputlen","maxlen","default_value","list_of_values","format","sortorder",
     "db_calc","formula_calc","test_avg","test_range","test_values",
     "description","tBA_tag","tBA_type");

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

        // loop through all fields, place in sort array, sort in order, and replace positions to match
        $sortpos = array();
		for ($cnt = 0; $cnt < $custom_params_limit; $cnt++)
		{
		  $postname=$cnt . "__tag";
		  $postpos=$cnt . "__position";
		  if (isset($_POST[$postname]) && $_POST[$postname] != NULL)
		    if ($_POST[$postpos] == "")
		      // set to high number to sort at end, as not defined
		      $sortpos[$postpos] = 250;
		    else
		      $sortpos[$postpos] = $_POST[$postpos];
		}
		// sort function for $sortpos array
		asort($sortpos);
		// replace array elements with sorted order
		$cnt=0;
		foreach ($sortpos as $sortp=>$sortval)
		  $_POST[$sortp]=$cnt++;
        // end of position sorting

		// load form fields
		for ($cnt = 0; $cnt < $custom_params_limit; $cnt++)
		{
		  $formfields = fields_load("post", $custom_param, $cnt . "__");

		  // if not null, create or insert
		  if ($formfields["tag"] != NULL )
		  {
		    // check if tag is well-formed
            $regexp = "/[ ?\"':;!@#\$%^&\*\(\)\[\]\{\}]/";
            if (! preg_match($regexp, $formfields["tag"]))
            {
              // Unneeded with case-insensitive char sets that are default for Maria DB
              // add tag_lower -- lowercase tag that serves to check for duplicate columns
              // $formfields["tag_lower"] = strtolower($formfields["tag"]);

              // add vargroup
		      $formfields["vargroup"] = $vargroup;
		      // specify where clause before updating row
		      $where = array( "vargroup"=> $vargroup, "tag"=> $formfields["tag"]);
		      db_update_or_create("custom_param", $where, $formfields);
		    }
		    else
		      die( showerror("Tag '{$formfields["tag"]}' may not contain spaces or special characters. Please re-enter."));
		  }

		} // end of for

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
  	print "<form method=\"POST\" action=\"/{$pagename}.php?edit=2&vargroup={$vargroup}\">\n";


  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/{$pagename}.php?vargroup={$vargroup}") . "\n";

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

  // if tBA, special instructions
  if (substr($vargroup,0,3) == "tBA")
  {
     print "\n<b>NOTE</b>: The Blue Alliance or \"FIRST\" custom parameter tags are preceeded by \"f_\" to help";
     print "distinguish them from other system tags and columns.<br>\n";
     print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A typical format for a real number is %.2f<br><br>\n";
  }

  // if in edit mode, provide note on position and delete
  if ($edit)
  {
    print "\n<b>NOTE:</b> decimal values can be used in position to reorder.\n";
    print "To delete a tag, click on link when not in edit and delete from the single-edit page.<br><br>\n";
  }

  // inline function to render page rows
  function custom_render_row ($edit, $options, $row, $editprefix=NULL)
  {
    global $vargroup;
    global $dbtypes;

    // Use tabtextfield($edit, $options, $data, $fieldname, $fieldtag, $size, $maxlenth, $defvalue, $editprefix)
    // for each field

    // set special URL tag options
    $tagoptions = $options;
    if (!($edit) && isset($row["tag"]))
      $tagoptions["href"] = "/{$pagename}-single.php?vargroup={$vargroup}&tag={$row["tag"]}";

    // special formating for tBA field mapping
    if (substr($vargroup,0,3) == "tBA")
      print "<tr align=\"left\">\n"
      . tabtextfield($edit,$tagoptions,$row, "tag","Tag",15,20,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "position","Pos",3,3,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "tBA_tag","tBA Tag",25,50,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "tBA_type","tBA Type",10,10,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "used","Usd",1,1,1,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "entrytype","Ent<br>Typ",1,1,"R",NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "dbtype","DB<br>type",8,10,"varchar",NULL,$dbtypes,$editprefix)
      . tabtextfield($edit,$options,$row, "maxlen","Max<br>Len",2,2,3,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "display","Display",15,20,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "heading","Heading",14,10,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "format","Format",10,10,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "sortorder","Sort",1,1,"a",NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "avg_calc","Avg<br>Calc",10,200,"Std",NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "test_avg","TestAvg",3,3,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "test_range","TestRng",3,3,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "test_values","TestValues",10,200,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "description","Description",10,200,NULL,NULL,NULL,$editprefix)
      . "</tr>\n\n";
    else
      print "<tr align=\"left\">\n"
      . tabtextfield($edit,$tagoptions,$row, "tag","Tag",15,20,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "position","Pos",3,3,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "used","Usd",1,1,1,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "entrytype","Ent<br>Typ",1,1,"D",NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "dbtype","DB<br>type",8,10,"varchar",NULL,$dbtypes,$editprefix)
      . tabtextfield($edit,$options,$row, "display","Display",15,20,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "heading","Heading",14,10,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "inputlen","Inp<br>Len",2,2,3,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "maxlen","Max<br>Len",2,2,3,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "default_value","Def<br>Val",10,20,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "list_of_values","List<br>of Val",10,100,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "format","Format",10,10,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "sortorder","Sort",1,1,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "db_calc","DB Calc",10,50,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "formula_calc","Formula<br>Calc",10,200,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "avg_calc","Avg<br>Calc",10,200,"Std",NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "test_avg","TestAvg",3,3,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "test_range","TestRng",3,3,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "test_values","TestValues",10,200,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "description","Description",10,200,NULL,NULL,NULL,$editprefix)
      . "</tr>\n\n";
  }

  // render header
  // field options
  $options["header"] = TRUE;  // add header
  $options["th"] = TRUE;  // add header
  $row = array();
  custom_render_row ($edit, $options, $row);

  // get custom_param details define result set
  $query = "select ". fields_insert("nameonly",NULL,$custom_param) . " from custom_param where vargroup = \"{$vargroup}\"";
  $query .= " order by position";
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
    custom_render_row ($edit, $options, $row, $cnt . "__");
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
  print dblockshowedit($edit, $dblock, "/{$pagename}.php?vargroup={$vargroup}") . "\n";

  // return and home buttons
  print "<br><br><a href=\"/admin.php\">Return to Admin</a><br>\n";
  print "<a href=\"/\">Return to Home</a>\n";


  // close the form if in edit mode
  if ($edit) print "\n</form>\n";

  // print "</tr>\n</table>\n";

} // end not admin

   pfooter();
  ?>