<?php
  // $Revision: 2.1 $
  // $Date: 2010/04/22 04:00:55 $
  //
  // Competition System - Custom Parameter Single Tag Entry
  //
  require "page.inc";

  // get variables, checking for existance
  if(isset($_GET["vargroup"])) $vargroup=$_GET["vargroup"]; else $vargroup="Bot";
  if(isset($_GET["tag"])) $tag=$_GET["tag"]; else $tag=NULL;
  if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;

  // header and setup
  pheader("Custom Parameters Setup - Group: {$vargroup}, Tag: {$tag}");
  $connection = dbsetup();

  // initialize variables and arrays

  // define lock array, fields arrays
  //   the arrays set how array-based functions for lock and field editing work
  $dblock = array("table"=>"custom_param","where"=>"vargroup = '{$vargroup}' and tag = '{$tag}'");
  $custom_param = array("tag","position","used","vargroup","entrytype","dbtype","display","inputlen","maxlen",
     "default_value","list_of_values","db_calc","formula_calc","test_avg","test_range","test_values",
     "description","tBA_tag","tBA_type");

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

        // add vargroup
        $formfields["vargroup"] = $vargroup;

		$query = "update custom_param set " . fields_insert("update",$formfields) . " where tag = '{$tag}'";
		// process query
		if (debug()) print "<br>DEBUG-customparam-single: " . $query . "<br>\n";
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
  $query = "select ". fields_insert("nameonly",NULL,$custom_param) . " from custom_param";
  $query .= " where vargroup = '{$vargroup}' and tag = '{$tag}'";
  if (debug()) print "<br>DEBUG-template: " . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // get row
  $row = mysqli_fetch_array($result);

  // field options
  $options["tr"] = 1;  // add tr tags

  $desc_options = $options;
  $desc_options["notagabove"] = TRUE;

  // if in edit mode, signal save with edit=2
  if ($edit)
  	print "<form method=\"POST\" action=\"/customparam-single.php?vargroup={$vargroup}&tag={$tag}&edit=2\">\n";

  // Use tabtextfield($edit, $options, $data, $fieldname, $fieldtag, $size, $maxlenth, $defvalue, $lov, $editprefix)
  // for each field


      if ($vargroup == "tBA")
        print tabtextfield($edit,$options,$row, "tag","Tag",15,20,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "position","Pos",3,3,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "tBA_tag","tBA Tag",40,50,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "tBA_type","tBA Type",10,10,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "used","Used",1,1,1,NULL,NULL)
        . tabtextfield($edit,$options,$row, "entrytype","EntTyp",1,1,"D",NULL,NULL)
        . tabtextfield($edit,$options,$row, "dbtype","DB type",10,10,"varchar",NULL,NULL)
        . tabtextfield($edit,$options,$row, "maxlen","MaxLen",2,2,3,NULL,NULL)
        . tabtextfield($edit,$options,$row, "display","Display",20,20,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_avg","TestAvg",3,3,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_range","TestRng",3,3,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_values","TestValues",50,200,NULL,NULL,NULL)
        . tabtextarea($edit,$desc_options,$row, "description","Description",6,50,NULL,NULL,NULL)
        . "</tr>\n\n";
      else
        print tabtextfield($edit,$options,$row, "tag","Tag",20,20,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "position","Pos",3,3,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "used","Used",1,1,1,NULL,NULL)
        . tabtextfield($edit,$options,$row, "entrytype","EntTyp",1,1,"D",NULL,NULL)
        . tabtextfield($edit,$options,$row, "dbtype","DB type",10,10,"varchar",NULL,NULL)
        . tabtextfield($edit,$options,$row, "display","Display",20,20,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "inputlen","InpLen",2,2,3,NULL,NULL)
        . tabtextfield($edit,$options,$row, "maxlen","MaxLen",2,2,3,NULL,NULL)
        . tabtextfield($edit,$options,$row, "default_value","Def Val",10,20,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "list_of_values","List of Val",50,100,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "db_calc","DB Calc",50,50,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "formula_calc","Formula Calc",10,200,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_avg","TestAvg",3,3,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_range","TestRng",3,3,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_values","TestValues",50,200,NULL,NULL,NULL)
        . tabtextarea($edit,$desc_options,$row, "description","Description",6,50,NULL,NULL,NULL)
      . "\n\n";

  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/customparam-single.php?vargroup={$vargroup}&tag={$tag}") . "\n";

  // return and home buttons
  print "<br><br><a href=\"/customparam.php?vargroup={$vargroup}\">Return to Custom Parms</a>\n";
  print "&nbsp;&nbsp;&nbsp;<a href=\"/admin.php\">Return to Admin</a><br>\n";
  print "<a href=\"/\">Return to Home</a><br><br>\n";

  // finish table and continue
  print "</td></tr></table>\n";


  // close the form if in edit mode
  if ($edit) print "\n</form>\n";

  print "</tr>\n</table>\n";

   pfooter();
  ?>