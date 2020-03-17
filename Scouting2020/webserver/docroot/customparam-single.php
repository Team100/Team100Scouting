<?php
  // $Revision: 2.1 $
  // $Date: 2010/04/22 04:00:55 $
  //
  // Competition System - Custom Parameter Single Tag Entry
  //
  require "page.inc";
  $pagename = "customparam-single";

  // get variables, checking for existance
  if(isset($_GET["vargroup"])) $vargroup=$_GET["vargroup"]; else $vargroup="Bot";
  if(isset($_GET["tag"])) $tag=$_GET["tag"]; else $tag=NULL;
  if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;

  // header and setup
  pheader("Custom Parameters Setup - Group: {$vargroup}, Tag: {$tag}");
  $connection = dbsetup();

// if not administrator, display error.  Otherwise show admin section.
if (! $admin)
  print "<h3>You must be an administrator to use this page.</h3>\n";
else
{
  // initialize variables and arrays
  $delete = 0;  // needed if delete is supported
  $dbtypes = array("varchar","int","real","boolean");

  // define lock array, fields arrays
  //   the arrays set how array-based functions for lock and field editing work
  $dblock = array("table"=>"custom_param","where"=>"vargroup = '{$vargroup}' and tag = '{$tag}'");
  $custom_param = array("tag","position","used","vargroup","entrytype","dbtype","display","heading",
     "inputlen","maxlen","default_value","list_of_values","format","sortorder",
     "db_calc","formula_calc","test_avg","test_range","test_values",
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

	    // process query
	    if (debug()) print "<br>DEBUG-{$pagename}: " . $query . "<br>\n";
	    if (! (@mysqli_query ($connection, $query) ))
		    dbshowerror($connection, "die");

        // commit
	    if (! (@mysqli_commit($connection) ))
		    dbshowerror($connection, "die");

	}

 	// delete operation
  	if (isset($_POST["op"]) && ($_POST["op"] == "Delete"))
	{
  		// check row
 		dblock($dblock,"check");

        // add vargroup
        $formfields["vargroup"] = $vargroup;

	    $query = "delete from custom_param where tag = '{$tag}'";

	    // process query
	    if (debug()) print "<br>DEBUG-{$pagename}: " . $query . "<br>\n";
	    if (! (@mysqli_query ($connection, $query) ))
		    dbshowerror($connection, "die");

        // commit
	    if (! (@mysqli_commit($connection) ))
		    dbshowerror($connection, "die");

        // set delete flag
        $delete = 1;

        // inform user and provide return.

        print "<br>Deleted tag '{$tag}'.\n";
        print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        print "<a href=\"/customparam.php?vargroup={$vargroup}\">Return to Custom Parameters</a> \n";
        print "<br><br>\n";

	}

	// abondon lock
	dblock($dblock,"abandon");

    // update completed, reset edit mode
    $edit = 0;
   }

   // lock tables if in edit mode
   if ($edit) dblock($dblock,"lock");  // lock row with current user id


// if delete is set, skip rendering
if (! ($delete))
{

  //
  // top of form rendering
  //

  //
  // create page
  //

  // if in edit mode, signal save with edit=2
  if ($edit)
  	print "<form method=\"POST\" action=\"/{$pagename}.php?vargroup={$vargroup}&tag={$tag}&edit=2\">\n";


  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/{$pagename}.php?vargroup={$vargroup}&tag={$tag}") . "\n";

  // add delete button
  if ($edit) print "&nbsp;&nbsp;<input type=\"submit\" name=\"op\" VALUE=\"Delete\" ALIGN=middle BORDER=0>\n";


  // return and home buttons
  print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"/customparam.php?vargroup={$vargroup}\">Return to Custom Parms</a>\n";
  print "&nbsp;&nbsp;&nbsp;<a href=\"/admin.php\">Return to Admin</a>\n";
  print "&nbsp;&nbsp;&nbsp; <a href=\"/\">Return to Home</a><br><br>\n";




  // if tBA, special instructions
  if (substr($vargroup,0,3) == "tBA")
  {
     print "\nNOTE: The Blue Alliance or \"FIRST\" custom parameter tags are preceeded by \"f_\" to help";
     print "distinguish them from other system tags and columns. ";
     print "A typical format for a real number is %.2f<br><br>\n";
  }

print "
<!----- Top of page ----->
<table valign=\"top\">
<tr valign=\"top\">
<td>
"; // end of print


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


  // Use tabtextfield($edit, $options, $data, $fieldname, $fieldtag, $size, $maxlenth, $defvalue, $lov, $editprefix)
  // for each field


      if (substr($vargroup,0,3) == "tBA")
        print tabtextfield($edit,$options,$row, "tag","Tag",15,20,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "position","Pos",3,3,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "tBA_tag","tBA Tag",40,50,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "tBA_type","tBA Type",10,10,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "used","Used",1,1,1,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "entrytype","EntTyp",1,1,"R",NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "dbtype","DB type",10,10,NULL,"varchar",$dbtypes,NULL)
        . tabtextfield($edit,$options,$row, "maxlen","MaxLen",2,2,3,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "display","Display",20,20,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "heading","Heading",14,10,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "format","Format",10,10,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "sortorder","Sort",1,1,"a",NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "avg_calc","Avg Calc",10,200,"Std",NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_avg","TestAvg",3,3,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_range","TestRng",3,3,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_values","TestValues",50,200,NULL,NULL,NULL,NULL)
        . tabtextarea($edit,$desc_options,$row, "description","Description",6,50,NULL,NULL,NULL,NULL)
        . "</tr>\n\n";
      else
        print tabtextfield($edit,$options,$row, "tag","Tag",20,20,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "position","Pos",3,3,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "used","Used",1,1,1,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "entrytype","EntTyp",1,1,"D",NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "dbtype","DB type",10,10,NULL,"varchar",$dbtypes,NULL)
        . tabtextfield($edit,$options,$row, "display","Display",20,20,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "heading","Heading",14,10,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "inputlen","InpLen",2,2,3,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "maxlen","MaxLen",2,2,3,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "default_value","Def Val",10,20,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "list_of_values","List of Val",50,100,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "format","Format",10,10,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "sortorder","Sort",1,1,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "db_calc","DB Calc",50,50,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "formula_calc","Formula Calc",10,200,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "avg_calc","Avg Calc",50,200,"Std",NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_avg","TestAvg",3,3,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_range","TestRng",3,3,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "test_values","TestValues",50,200,NULL,NULL,NULL,NULL)
        . tabtextarea($edit,$desc_options,$row, "description","Description",6,50,NULL,NULL,NULL,NULL)
      . "\n\n";




  // finish table and continue
  print "</td></tr></table>\n";

  print "</tr>\n</table>\n";

  print "<br><br>\n";

  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/{$pagename}.php?vargroup={$vargroup}&tag={$tag}") . "\n";

  // add delete button
  if ($edit) print "&nbsp;&nbsp;<input type=\"submit\" name=\"op\" VALUE=\"Delete\" ALIGN=middle BORDER=0>\n";

  // return and home buttons
  print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"/customparam.php?vargroup={$vargroup}\">Return to Custom Parms</a>\n";
  print "&nbsp;&nbsp;&nbsp;<a href=\"/admin.php\">Return to Admin</a>\n";
  print "&nbsp;&nbsp;&nbsp; <a href=\"/\">Return to Home</a><br><br>\n";

  // close the form if in edit mode
  if ($edit) print "\n</form>\n";

} // end of if delete
} // end of if admin

   pfooter();
  ?>