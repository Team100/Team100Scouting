<?php
  // $Revision: 2.1 $
  // $Date: 2010/04/22 04:00:55 $
  //
  // Competition System - Custom Parameter Single Tag Entry
  //
  require "page.inc";
  $pagename = "docadmin-single";

  // get variables, checking for existance
  if(isset($_GET["vargroup"])) $vargroup=$_GET["vargroup"]; else $vargroup="Bot";
  if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;

  // check post then get for doctag:
  if(isset($_POST["doctag"])) $doctag=$_POST["doctag"];
  else if(isset($_GET["doctag"])) $doctag=$_GET["doctag"];
  else $doctag="";

  // header and setup
  pheader("Documentation Admin - DocTag: {$doctag}");
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
  $dblock = array("table"=>"documentation","where"=>"doctag = '{$doctag}'");
  $doc = array("doctag","default_docnode","admin","title","topic","doctext");

  // handle update if returning from edit mode
  if ($edit == 2)   // performs database save
  {
  	// load operation
  	if (isset($_POST["op"]) && ($_POST["op"] == "Save"))
	{
  		// check row
 		dblock($dblock,"check");

		// load form fields
		$formfields = fields_load("post", $doc);

	    // check if tag is well-formed
        $regexp = "/[ ?\"':;!@#\$%^&\*\(\)\[\]\{\}]/";

        if ($formfields["doctag"] != "" && (! preg_match($regexp, $formfields["doctag"])))
        {
          // add vargroup
		  //$formfields["vargroup"] = $vargroup;
		  // specify where clause before updating row
		  $where = array( "doctag"=> $formfields["doctag"]);
		  db_update_or_create("documentation", $where, $formfields);
		}
		else
		  die( showerror("Doc Tag '{$formfields['doctag']}' may not contain spaces, special characters, or be blank. Please re-enter.\n" ));

        // set doctag for further down
        $doctag = $formfields["doctag"];

	    // process query
	    //if (debug()) print "<br>DEBUG-{$pagename}: " . $query . "<br>\n";
	    //if (! (@mysqli_query ($connection, $query) ))
		//    dbshowerror($connection, "die");

        // commit
	    if (! (@mysqli_commit($connection) ))
		    dbshowerror($connection, "die");

	}

 	// delete operation
  	if (isset($_POST["op"]) && ($_POST["op"] == "Delete"))
	{

	    // issue warning confirmation
	    if (! isset($_GET['deleteconfirm']))
	    {
	      print "<form method=\"POST\" action=\"/{$pagename}.php?doctag={$doctag}&edit=2&deleteconfirm=1\">\n";
	      print "<b>Are you sure you want to delete DocTag {$doctag}?</b>&nbsp;&nbsp;";
	      print "<input type=\"submit\" name=\"op\" VALUE=\"Delete\" ALIGN=middle BORDER=0>\n";
	      print "<input type=\"submit\" name=\"op\" VALUE=\"Cancel\" ALIGN=middle BORDER=0>\n";
	      print "</form>";
	      $delete = 1;
	    }
	    else
	    {

  		  // check row
 		  dblock($dblock,"check");

          // add vargroup
          //$formfields["vargroup"] = $vargroup;

	      $query = "delete from documentation where doctag = '{$doctag}'";

	      // process query
	      if (debug()) print "<br>DEBUG-{$pagename}: " . $query . "<br>\n";
	      if (! (@mysqli_query ($connection, $query) ))
		      dbshowerror($connection, "die");

          // commit
	      if (! (@mysqli_commit($connection) ))
		      dbshowerror($connection, "die");

  	      // abondon lock
	      dblock($dblock,"abandon");

          // set delete flag
          $delete = 0;

          // inform user and provide return.

          print "<br>Deleted doctag '{$doctag}'.\n";
          print "<br><br>\n";

          // reset doctag
          $doctag="";
        } // end else to isset

	} // end of if delete
	else
  	  // abondon lock
	  dblock($dblock,"abandon");

    // update completed, reset edit mode
    $edit = 0;
   }

   // lock tables if in edit mode
   if ($edit) dblock($dblock,"lock");  // lock row with current user id

  // get list of doctags
  $doctags = array ();
  $query = "select doctag from documentation order by doctag";
  if (debug()) print "<br>DEBUG-{$pagename}:" . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // get rows and build $doctags array
  while($row = mysqli_fetch_array($result))
	$doctags[] = $row['doctag'];

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
  	print "<form method=\"POST\" action=\"/{$pagename}.php?doctag={$doctag}&edit=2\">\n";
  else
  {
  	print "<form method=\"POST\" action=\"/{$pagename}.php?\">\n";
  	print "<select name=\"doctag\">\n";

    // loop through entries
    foreach($doctags as $tagselect)
    {
      if ($tagselect == $doctag) $selected="selected"; else $selected="";
      print "<option value=\"{$tagselect}\" {$selected}>{$tagselect}</option>\n";
    }
    print "</select>&nbsp;&nbsp;\n";

  	print "<input type=\"submit\" name=\"op\" VALUE=\"Submit\" ALIGN=middle BORDER=0>\n";
    print "</form>\n";
    print "<br>\n";
  } // else edit

  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/{$pagename}.php?doctag={$doctag}") . "\n";

  // add delete button
  if ($edit) print "&nbsp;&nbsp;<input type=\"submit\" name=\"op\" VALUE=\"Delete\" ALIGN=middle BORDER=0>\n";

  // add new button
  if (! ($edit)) print "&nbsp;&nbsp;<a href=\"/{$pagename}.php?edit=1&doctag=\">New Doc</a>\n";


  // return and home buttons
  print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"/docnodeadmin.php?doctag={$doctag}\">Doc Node Admin</a>\n";
  print "&nbsp;&nbsp;&nbsp;<a href=\"/admin.php\">Return to Admin</a>\n";
  print "&nbsp;&nbsp;&nbsp; <a href=\"/\">Return to Home</a><br><br>\n";



print "
<!----- Top of page ----->
<table valign=\"top\">
<tr valign=\"top\">
<td>
"; // end of print


  // get documentation details define result set
  $query = "select ". fields_insert("nameonly",NULL,$doc) . " from documentation";
  $query .= " where doctag = '{$doctag}'";
  if (debug()) print "<br>DEBUG-template: " . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // get row
  $row = mysqli_fetch_array($result);

  // field options
  $options["tr"] = 1;  // add tr tags

  $text_options = $options;
  $text_options["notagabove"] = TRUE;


  // Use tabtextfield($edit, $options, $data, $fieldname, $fieldtag, $size, $maxlenth, $defvalue, $lov, $editprefix)
  // for each field

        print tabtextfield($edit,$options,$row, "doctag","Doc Tag:",20,20,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "default_docnode","Default Node:",20,20,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "admin","Admin:",1,1,0,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "title","Title",70,70,NULL,NULL,NULL,NULL)
        . tabtextfield($edit,$options,$row, "topic","Topic",70,70,NULL,NULL,NULL,NULL)
        . tabtextarea ($edit,$text_options,$row, "doctext","Text",30,100,NULL,NULL,NULL,NULL)
      . "\n\n";



  // finish table and continue
  print "</td></tr></table>\n";

  print "</tr>\n</table>\n";

  print "<br><br>\n";

  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/{$pagename}.php?&doctag={$doctag}") . "\n";

  // add delete button
  if ($edit) print "&nbsp;&nbsp;<input type=\"submit\" name=\"op\" VALUE=\"Delete\" ALIGN=middle BORDER=0>\n";

  // add new button
  if (! ($edit)) print "&nbsp;&nbsp;<a href=\"/{$pagename}.php?edit=1&doctag=\">New Doc</a>\n";

  // return and home buttons
  print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"/docnodeadmin.php?doctag={$doctag}\">Doc Node Admin</a>\n";
  print "&nbsp;&nbsp;&nbsp;<a href=\"/admin.php\">Return to Admin</a>\n";
  print "&nbsp;&nbsp;&nbsp; <a href=\"/\">Return to Home</a><br><br>\n";

  // close the form if in edit mode
  if ($edit) print "\n</form>\n";

} // end of if delete
} // end of if admin

   pfooter();
  ?>