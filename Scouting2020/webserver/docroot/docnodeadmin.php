<?php
// $Revision: 2.1 $
// $Date: 2010/04/22 04:00:55 $
//
// Competition System - Documentation admin
//
// Node / tree management scren
//
require "page.inc";
$pagename = "docnodeadmin";

// get variables, checking for existance
if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;

// check post then get for parent:
if(isset($_POST["parent"])) $parent=$_POST["parent"];
else if(isset($_GET["parent"])) $parent=$_GET["parent"];
else $parent="";

// header and setup
pheader("Documentation Admin: Nodes");
$connection = dbsetup();

// if not administrator, display error.  Otherwise show admin section.
if (! $admin)
  print "<h3>You must be an administrator to use this page.</h3>\n";
else
{
  // initialize variables and arrays
  $docnodes_beyond = 5;  // the number of nodes to paint beyond the end of data

  // define lock array, fields arrays
  //   the arrays set how array-based functions for lock and field editing work
  $docnodes = array("docnode", "parent", "position", "doctag", "admin", "topic");

  // because we're dealing with multiple rows, use a process lock
  $dblock = array("table"=>"process_lock","where"=>"lock_id = 'docnode'");

  // handle update if returning from edit mode
  if ($edit == 2)   // performs database save
  {
  	// load operation
  	if (isset($_POST["op"]) && ($_POST["op"] == "Save"))
	{
  		// check row
  		dblock($dblock,"check");

        // loop through all fields, place in sort array, sort in order, and replace positions to match
        //  group by parent
        $sortpos = array();
        $cnt=0;
        while($cnt < $docnodes_limit || isset($_POST[$cnt . "__docnode"]))
		{
		  $postname=$cnt . "__parent";
		  $nodetag=$cnt . "__docnode";
		  $postpos=$cnt . "__position";
		  if (isset($_POST[$postname]) && $_POST[$postname] != NULL)
		  {
		    $parentsort=$_POST[$postname];
		    if ($_POST[$postpos] == "")
		      // set to high number to sort at end, as not defined
		      $sortpos[$parentsort][$postpos] = 250;
		    else
		      $sortpos[$parentsort][$postpos] = $_POST[$postpos];
		  }
		  $cnt++;
		}
		// set $docnodes_tot
		$docnodes_tot = $cnt;

		// sort function for $sortpos array by parent
		foreach($sortpos as $parname=>$pararray)
			asort($sortpos[$parname]);

		// replace array elements with sorted order, by parent
		foreach($sortpos as $parname=>$pararray)
        {
		  $cnt=1;
		  foreach ($sortpos[$parname] as $sortp=>$sortval)
		    $_POST[$sortp]=$cnt++;
          // end of position sorting
        }

		// load form fields
		for ($cnt = 0; $cnt <= $docnodes_tot; $cnt++)
		{
		  $formfields = fields_load("post", $docnodes, $cnt . "__");

		  // if not null, create or insert
		  if ($formfields["docnode"] != NULL )
		  {
		    // check if nodetag is well-formed
            $regexp = "/[ ?\"':;!@#\$%^&\*\(\)\[\]\{\}]/";
            if (preg_match($regexp, $formfields["docnode"]))
               die( showerror("Node '{$formfields['docnode']}' may not contain spaces or special characters. Please re-enter.\n" ));
            else if (preg_match($regexp, $formfields["parent"]))
               die( showerror("Parent '{$formfields['parent']}' may not contain spaces or special characters. Please re-enter.\n" ));
            else
            {
		      // specify where clause before updating row
		      $where = array( "docnode"=> $formfields["docnode"]);
		      db_update_or_create("docnode", $where, $formfields);
		    }

		  }

		} // end of for

		// commit
		if (! (@mysqli_commit($connection) ))
			dbshowerror($connection, "die");
	} // end of edit


 	// delete operation
  	if (isset($_POST["op"]) && ($_POST["op"] == "DeleteNode"))
	{
      // look for delete variable
	  if (isset($_GET["deletenode"]))
	  {
		$deletenode = $_GET["deletenode"];

  		// check row
 		dblock($dblock,"check");

	    $query = "delete from docnode where docnode = '{$deletenode}'";

	    // process query
	    if (debug()) print "<br>DEBUG-{$pagename}: " . $query . "<br>\n";
	    if (! (@mysqli_query ($connection, $query) ))
		    dbshowerror($connection, "die");

        // commit
	    if (! (@mysqli_commit($connection) ))
		    dbshowerror($connection, "die");

        // set delete flag
        $delete = 0;

        // inform user and provide return.

        print "<br>Deleted Doc Node '{$deletenode}'.\n";
        print "<br><br>\n";
      } // issent
	} // delete

	// abondon lock
	dblock($dblock,"abandon");

    // update completed, reset edit mode
    $edit = 0;
   }

   // lock tables if in edit mode
   if ($edit) dblock($dblock,"lock");  // lock row with current user id


  // get list of doctags and titles
  $doctags = array ("");  // start with first element blank
  $doctitles = array();
  $query = "select doctag, title from documentation order by doctag";
  if (debug()) print "<br>DEBUG-{$pagename}:" . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // get rows and build $doctags array
  while($row = mysqli_fetch_array($result))
  {
	$doctags[] = $row['doctag'];
	$doctitles += [ $row['doctag'] => $row['title'] ];
  }

  // get list of docnode parents
  $docnodes_lov = array ("");  // start with first element blank
  $query = "select distinct parent from docnode order by docnode";
  if (debug()) print "<br>DEBUG-{$pagename}:" . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // get rows and build $doctags array
  while($row = mysqli_fetch_array($result))
	$docnodes_lov[] = $row['parent'];

//
// top of form rendering
//

  print "\n\n";
  // if in edit mode, signal save with edit=2, otherwise show selector
  if ($edit)
  	print "<form method=\"POST\" action=\"/{$pagename}.php?edit=2&parent={$parent}\">\n";
  else
  {
  	print "<form method=\"POST\" action=\"/{$pagename}.php?\"?parent={$parent}>\n";
  	print "Nodes under this parent: &nbsp; <select name=\"parent\">\n";

    // loop through entries
    foreach($docnodes_lov as $tagselect)
    {
      if ($tagselect == $parent) $selected="selected"; else $selected="";
      print "<option value=\"{$tagselect}\" {$selected}>{$tagselect}</option>\n";
    }
    print "</select>&nbsp;&nbsp;\n";

  	print "<input type=\"submit\" name=\"op\" VALUE=\"Submit\" ALIGN=middle BORDER=0>\n";
    print "</form>\n";
    print "<br>\n";
  } // else edit


  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/{$pagename}.php?parent={$parent}") . "\n";

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

  // if in edit mode, provide note on position and delete
  if ($edit)
  {
    print "\n<b>NOTE:</b> decimal values can be used in position to reorder.\n";
    print "<br><br>\n";
  }

  // inline function to render page rows
  function docnode_render_row ($edit, $options, $row, $editprefix=NULL)
  {
    global $doctags, $doctitles;
    global $pagename;

    // Use tabtextfield($edit, $options, $data, $fieldname, $fieldtag, $size, $maxlenth, $defvalue, $editprefix)
    // for each field

    // set special URL tag options
    $tagoptions = $options;
    if (!($edit) && isset($row["doctag"]))
      $tagoptions["href"] = "/docadmin-single.php?doctag={$row["doctag"]}";

    // check and set title
    if (isset($row['doctag']) && isset($doctitles[$row['doctag']]))
      $title=$doctitles[$row['doctag']];
    else $title=NULL;

      print "<tr align=\"left\">\n"
      . tabtextfield($edit,$options,$row, "docnode","Doc Node",20,20,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "parent","Parent",20,20,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "position","Pos",3,3,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "admin","Adm",1,1,"0",NULL,NULL,$editprefix)
      . tabtextfield($edit,$options,$row, "topic","Topic Title",50,70,NULL,NULL,NULL,$editprefix)
      . tabtextfield($edit,$tagoptions,$row, "doctag","DocTag",20,20,NULL,NULL,$doctags,$editprefix)
      . tabtextfield(0,$options,$row, "title","Title",25,50,$title,NULL,NULL,$editprefix)
      . "\n";

      if (($edit) && isset($options["th"]) && $options["th"] != TRUE)
        if (isset($row['docnode']) && $row['docnode'] != NULL)
        {
          print "<td><input type=\"submit\" name=\"op\" formaction=\"/{$pagename}.php?edit=2&deletenode={$row['docnode']}\" ";
          print "VALUE=\"DeleteNode\" ALIGN=middle BORDER=0></td>\n";
        }

      print "</tr>\n\n";

  }

  // render header
  // field options
  $options["header"] = TRUE;  // add header
  $options["th"] = TRUE;  // add header
  $row = array();
  docnode_render_row ($edit, $options, $row);

  // get docnode details define result set -- if parent is empty, bring up whole list
  if ($parent == "")
  {
    $query = "select ". fields_insert("nameonly",NULL,$docnodes) . " from docnode";
    $query .= " order by parent, position";
  }
  else
  {
    // hairy recursive query

    // set up field strings
    $fieldstr = fields_insert("nameonly",NULL,$docnodes);
    $fieldstr_d1 = fields_insert("nameonly",NULL,$docnodes, "d1.");
    $fieldstr_d2 = fields_insert("nameonly",NULL,$docnodes, "d2.");

    $query = "with recursive node({$fieldstr}, level) as ( ";
	$query .= "select {$fieldstr_d1}, 1 level from docnode d1 left join docnode d2 on d2.docnode = d1.parent ";
	$query .= "  where d1.parent = '{$parent}'  UNION all ";
	$query .= "select {$fieldstr_d1}, level+1 level from docnode d1 join node d2 on d2.docnode = d1.parent) ";
	$query .= "select {$fieldstr}, level from node order by level, position ";
  }

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
    docnode_render_row ($edit, $options, $row, $cnt . "__");
    $cnt++;
  }

  // set docnodes_total
  $docnodes_tot_limit = $cnt + $docnodes_beyond;

  // finish off form to end of data if in edit mode
  if ($edit)
  	while($cnt < $docnodes_limit || $cnt < $docnodes_tot_limit)
    {
      docnode_render_row ($edit, $options, $row, $cnt . "__");
      $cnt++;
    }

  // end table
  print "</table><br>\n";


  // add edit link or submit button
  print dblockshowedit($edit, $dblock, "/{$pagename}.php?parent={$parent}") . "\n";

  // return and home buttons
  print "<br><br><a href=\"/admin.php\">Return to Admin</a><br>\n";
  print "<a href=\"/\">Return to Home</a>\n";


  // close the form if in edit mode
  if ($edit) print "\n</form>\n";

  // print "</tr>\n</table>\n";

} // end not admin

   pfooter();
  ?>