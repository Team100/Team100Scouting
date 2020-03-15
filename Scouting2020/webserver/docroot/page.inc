<?php
//
// $Revision: 3.0 $
// $Date: 2016/03/14 22:56:41 $
//
// page.inc
//
// Functions to be used on every page.  Documentation shown above function (and available in compilation).
//

require "params.inc";


// software release version
$release_version="4.0.1";


//
// set time zone
//
date_default_timezone_set($sys_time_zone);


//
// function: debug
//  returns true if $debug is true, otherwise returns false
//
//  Typical use: if (debug()) print "\n<br>DEBUG-set-event: {$query} <br>\n";
//
// comment out TRUE to turn off
$debug=FALSE;
//$debug=TRUE;
//
//check $get variable for debug and turn to true (easy for on-the-fly)
if(isset($_GET['debug'])) $debug=TRUE;

//
function debug()
{
    global $debug;

    if ( $debug === TRUE ) return(TRUE); else return(FALSE);
}



//
// ****************************************************************************
//
// Database Functions
//
// 


// 
// function: dbshowerror($connection, $die) - show database error
//  $connection = mysql connection context
//  $die = if text flag set to "die", process terminates with this error 
//
//  Displays database error using showerror as the display mechanism.

function dbshowerror($connection, $die = NULL)
{
    if ( $die == "die")
      die(showerror("Fatal Database Error " . mysqli_errno($connection) . ", " . mysqli_error($connection)));
    else
      showerror("Non-fatal Database Error " . mysqli_errno($connection) . ", " . mysqli_error($connection));
}

//
// function: dbsetup()  - database start
//  Returns: $connection MySQL handle
//
//  Sets up single connection for database interaction.  $connection is returned.
//  Also loads sys_event_id, as it is needed everywhere in system
//

function dbsetup()
{
  // take variable values from the global space
  global $dbhost, $dbuser, $dbname, $dbpass;
  global $sys_event_id, $sys_event_name;
  
  // get connection
  if(!($connection = @ mysqli_connect($dbhost,$dbuser,$dbpass, $dbname)))
     die("Database Error:" . mysqli_connect_errno() . " : " . mysqli_connect_error());
     
  // turn autocommit off
  mysqli_autocommit($connection, FALSE);

  // load default event_id and name for this instance from database
  //$query="select value, name from system_value, event where skey = 'sys_event_id' and event_id = value";
  // use left join syntax for outer join
  $query="select value, name from system_value left join event on event_id = value where skey = 'sys_event_id'";

  if (debug()) print "\n<br>DEBUG-dbsetup: {$query} <br>\n";

  if (! ($result = @mysqli_query ($connection, $query) ))
  	dbshowerror($connection, "die");
  if ($row = mysqli_fetch_array($result))
  {
    $sys_event_id = $row["value"];
    $sys_event_name = $row["name"];
  }
  else
    $sys_event_id = "";
    
  mysqli_free_result($result);
  
  if ($sys_event_id == "") $sys_event_name = "No competition event selected !!";

 
  // check for autocommit off
  //if (debug()) 
  //  if (! ($result = @mysqli_query($connection, "SELECT @@autocommit")))
  //       dbshowerror($connection, "die");
  //  else
  //  {
  //    $row = mysqli_fetch_row($result);
  //    printf("Autocommit is %s<br>\n", $row[0]);
  //    mysqli_free_result($result);
  //  }

  return($connection);
}


//
// function: dblock($dblock, $op) - controls locks on rows: locks, checks for lock or abandons lock
//   $dblock array can be configured two ways:
//      1) An associative array that consists of two values:
//   	    table - table name in which to place lock
//          where - where clause to identify correct row(s)
//      2) An array of associative arrays that consist of the two values above.  In this case
//           the routine performs dblock function for each table/where clause in the array
//    $op - operation: "lock", "check", "abandon"
//      "lock" - lock the row with the current $user
//      "check" - check that the $user still holds a lock 
//      "abandon" - abandon or release the lock
//	"changedby" - update field 'updatedby' in the given table
//
// Implements poor-man's row-locking.  This single function performs all operations, via the $op directive.
//  Function looks for locked column and if locked column exists.
//
//  Can also be used with a "dblockshowedit" function, which will query for a lock and either show a "steal" page
//   or display Save and Cancel buttons.
//
//  Implementation note: there is a "semi-locked" state that occurs when a database throws an error.  
//   The user is instructed to go back, but may not.  In the case the user doesn't go back, 
//    we want new users to edit the page.  In the case where he does go back, we would like the user
//    to edit the page again. This situation is handled with a "semi lock" which is the username
//    preceeded by a '_' character.  Clear adds it, and dblockshowedit ignores the lock if _ preceeds.
//
// Note: Can't seem to find a mysqli describe function to test if the column is there
//
//
function dblock($dblock, $op)
{
    global $connection; // database connection
    global $user;      	// user id
    global $_GET;

    // if dblock var not set, show error
    if (! isset($dblock))
    {
    	showerror("DB lock function called with no DB Lock info.");
    	return;
    }

    // determine if we are called as a single table and convert to the multitable array
    if (isset($dblock["table"]))
    {
    	$working[0]["table"] = $dblock["table"];
    	$working[0]["where"] = $dblock["where"];
    }
    else
    	// assume an array instead
    	$working = $dblock;

    
    
    // loop through array locking each table/row
    foreach($working as $wlock)
    {
    	// look for row-locked user  
    	$query = "select locked from {$wlock["table"]} where {$wlock["where"]}";
    	if (debug()) print "<br>DEBUG-dblock: " . $query . "<br>\n";
	if (! ($result = @mysqli_query ($connection, $query) ))
		dbshowerror($connection, "die");

	// get row and field
	$rowret = TRUE;
	if ($row = mysqli_fetch_array($result))
		$rowlocked = $row["locked"];
        else 
        { 
          $rowret = FALSE;
          $rowlocked = NULL;
        }
        
	// perform various operations, per switch	    	
    	switch ($op)
    	{
    	   case "lock":
	    	// if row locked and not stealing, abort
	    	if ($rowlocked != "" && $rowlocked != $user && substr($rowlocked,0,1) != "_")
	    	   if (! isset($_GET["dblocksteal"]) || $_GET["dblocksteal"]!= "1")
			  die(showerror("Database row in {$wlock["table"]} is being edited by {$rowlocked}. You may steal the edit or wait (hit BACK on browswer and refresh)."));

	    	// add userid as locked user  
	    	if (! (@mysqli_query ($connection, "update {$wlock["table"]} set locked = '{$user}' where {$wlock["where"]}") ))
			dbshowerror($connection, "die");
		
	 	break;
	 	
	    case "check":
	    	// if row has returned, then check/abort. if row locked by another user, abort transaction. If not, replace
	    	//   user with semi-locked _user
	    	if ($rowret === TRUE)
	    	{
	    	  if ($rowlocked != $user && $rowlocked != "_" . $user)
	    	     if ($rowlocked == "" || $rowlocked == "_" . $user)
		 	die(showerror("Database row in {$wlock["table"]} was edited by someone else.  Please abort and return to Home Page!"));
	    	     else 
			die(showerror("Database row in {$wlock["table"]} has been locked by a different user - {$rowlocked}. Edit aborted.  Hit back on browser to continue."));
                  else
	    	  // add userid as semi-locked user  
//JLV clean	    	  if (! (@mysqli_query ($connection, "update {$wlock["table"]} set locked = NULL where {$wlock["where"]}") ))
	    	  if (! (@mysqli_query ($connection, "update {$wlock["table"]} set locked = '_{$user}' where {$wlock["where"]}") ))
			dbshowerror($connection, "die");	    
		}
		
		break;

	    case "abandon":
	    	// if row locked by this user, free row
	    	if ($rowlocked == $user || $rowlocked == "_" . $user)
		    	if (! (@mysqli_query ($connection, "update {$wlock["table"]} set locked = NULL where {$wlock["where"]}") ))
				dbshowerror($connection, "die");	
		break;
		
	    case "changedby":
	    	if ($rowlocked == $user)
	    	{
	    		global $maxlengthchangeby;
	    		
	    		if (! ($result = @mysqli_query ($connection, "select updatedby from {$wlock["table"]} where {$wlock["where"]}") ))
				dbshowerror($connection, "die");
			$row = mysqli_fetch_array($result);
			$updatedby = $row["updatedby"];
			
			$date = date('m/d.h:i');
			$updatedby = "{$user} {$date};{$updatedby}";
			$updatedby = substr ($updatedby, 0, $maxlengthchangeby);
				
		    	if (! (@mysqli_query ($connection, "update {$wlock["table"]} set updatedby = '{$updatedby}' where {$wlock["where"]}") ))
				dbshowerror($connection, "die");
		}
	    	break;
	 } // end of switch   
      } // end of foreach

      // commit
      if (! (@mysqli_commit($connection) ))
	dbshowerror($connection, "die");
	
}




//
// function: dblockshowedit($edit, $dblock, $urlroot)
//   $edit - flag is non-zero if editing
//   $dblock array can be configured two ways:
//      1) An associative array that consists of two values:
//   	    table - table name in which to place lock
//          where - where clause to identify correct row(s)
//      2) An array of associative arrays that consist of the two values above.  In this case
//           the routine performs dblock function for each table/where clause in the array
//   $urlroot - urlroot to connect to edit link (or override link)
//
//  Returns: string to display to user
//
// If in edit mode, show edit options.  If not in edit mode, check for lock and 
//   show editability to user
//
function dblockshowedit( $edit, $dblock, $urlroot )
{
    global $connection; // database connection
    global $user;      	// user id

    // if dblock var not set, show error
    if (! isset($dblock))
    {
    	showerror("DBshowedit function called with no DB Lock info.");
    	return;
    }
    
    // determine if we are called as a single table and convert to the multitable array
    if (isset($dblock["table"]))
    {
    	$working[0]["table"] = $dblock["table"];
    	$working[0]["where"] = $dblock["where"];
    }
    else
    	// assume an array instead
    	$working = $dblock;



    // if in edit mode, show button, otherwise check what type of edit to show
    if ($edit)
    {
	$string = "<INPUT TYPE=\"submit\" name=\"op\" VALUE=\"Save\" ALIGN=middle BORDER=0>\n"
	. "&nbsp;<INPUT TYPE=\"submit\" name=\"op\" VALUE=\"Cancel\" ALIGN=middle BORDER=0>\n";
    }
    else
    {
    	// loop through array locking each table/row
    	foreach($working as $wlock)
    	{
		// move through tables identifying any locking
		
		// look for row-locked user  
		if (! ($result = @mysqli_query ($connection, "select locked from {$wlock["table"]} where {$wlock["where"]}") ))
			dbshowerror($connection, "die");

		// get row and field
		if ($row = mysqli_fetch_array($result))
		  $rowlocked = $row["locked"];
		else $rowlocked = NULL;
		
		// if not null, not from the current user, and not semi-locked with '_' add to list
		if ($rowlocked != "" && $user != $row["locked"] && substr($rowlocked,0,1) != '_') 
		  $showlocked[] = array("table"=>$wlock["table"], "user"=>$row["locked"]);
	}

     	// if list exists, see how many elements and show user
     	if (isset ($showlocked))
     	{
     		// how many elements
		if (count($showlocked) > 1)
  		// multiple tables
		{
			$string = "Locked: ";
			foreach($showlocked as $locker)
				$string = $string . "{$locker["table"]} by {$locker["user"]},";
		
			$string = $string . " <a href=\"{$urlroot}\">Retry</a>\n";
			$string = $string . " &nbsp; <a href=\"{$urlroot}&edit=1&dblocksteal=1\">!Steal page!</a>\n";
		}
		else
			$string = "Locked by {$rowlocked}. &nbsp; <a href=\"{$urlroot}\">Retry</a>\n";
     		 	$string = $string . " &nbsp; <a href=\"{$urlroot}&edit=1&dblocksteal=1\">!Steal page!</a>\n";

     	}
     	else
     		$string = "<a href=\"{$urlroot}&edit=1\">Edit this page</a>\n";

    } // end of if edit else
    return ($string);
}



//
// function: showupdatedby($dblock)
// 
// if the user is an admin return a string of hwo has updated the table
// 	defined by the dblock paramaters (look above)
// $dblock is an array with keys of "table" and "where" that define what table is being accessed
//

function showupdatedby($dblock)
{
	global $admins;
	global $user;
	global $admin;
	global $connection;
	global $sys_event_id;
	
	if($admin)
	{
		// if dblock var not set, show error
		if (! isset($dblock))
		{
			showerror("DBshowedit function called with no DB Lock info.");
			return;
		}

		// determine if we are called as a single table and convert to the multitable array
		if (isset($dblock["table"]))
		{
			$working[0]["table"] = $dblock["table"];
			$working[0]["where"] = $dblock["where"];
		}
		else
		// assume an array instead
			$working = $dblock;
			
		foreach($working as $wlock)
		{    	
			$query="select updatedby from {$wlock["table"]} where {$wlock["where"]}";
			if (! ($result = @mysqli_query ($connection, $query) ))
				dbshowerror($connection, "die");
			$row = mysqli_fetch_array($result);
			print "<br>{$wlock["table"]} row updated by: {$row["updatedby"]}";
		}
	}
}


// *******
// Database update and data creation functions
//

// function: db_update($tablename, $table_identifiers, $db_array)
//   $tablename - table name in compsys for which array applies
//   $table_identifiers - formatted array of key/value pairs where the 
//        key is the db column name that is part of the identifier
//        (and thus the where clause), and the value is the data.
//   $db_data_array - tBA data, formatted as an array of key/value 
//        pairs where the key is the db column name and the value
//        is the data.
//
// Updates database with data
//   

function db_update($tablename, $table_identifiers, $db_array)
{
   global $connection;
   
   // set up dummy column for test
   $dummycol="";
   foreach($table_identifiers as $column=>$value)
     if ($dummycol == "") $dummycol = $column;

   // set where clauase
   $where = fields_insert("where", $table_identifiers, "");
    
   // update
   $query = "update {$tablename} set " . fields_insert("update", $db_array, "")
          .  " where " . $where;
   if (debug()) print "<br>DEBUG:page, db_update :" . $query . "<br>\n";
   if (! (@mysqli_query ($connection, $query) ))
     dbshowerror($connection, "die");
   
   return(TRUE);
}

// function: db_update_or_create($tablename, $table_identifiers, $db_array)
//   $tablename - table name in compsys for which array applies
//   $table_identifiers - formatted array of key/value pairs where the 
//        key is the db column name that is part of the identifier
//        (and thus the where clause), and the value is the data.
//   $db_data_array - tBA data, formatted as an array of key/value 
//        pairs where the key is the db column name and the value
//        is the data.
//
// Updates database with data
//   Queries for row with data.  If found, updates.  If not found, inserts.
//

function db_update_or_create($tablename, $table_identifiers, $db_array)
{
   global $connection;
   
   // set up dummy column for test
//   $dummycol="";
//   foreach($table_identifiers as $column=>$value)
//    if ($dummycol == "") $dummycol = $column;

   // set where clause
   $where = fields_insert("where", $table_identifiers, "");
   
   // select to see if update or insert
   $query = "select 'dummy' from {$tablename} where " . $where;
   if (debug()) print "<br>DEBUG:page,db_update_or_create, Select query: " . $query . "<br>\n";
   
   if (! ($result = @ mysqli_query ($connection, $query)))
  	  dbshowerror($connection, "die");
  	  
   // if return, update, otherwise insert
   if ($row = mysqli_fetch_array($result))
   {
     $query = "update {$tablename} set " . fields_insert("update", $db_array, "")
            .  " where " . $where;
     if (debug()) print "<br>DEBUG:page,db_update_or_create,update:" . $query . "<br>\n";
     if (! (@mysqli_query ($connection, $query) ))
       dbshowerror($connection, "die");
   } 
   else
   {
     $query = "insert into {$tablename} (" . fields_insert("nameonly", $db_array, "")
             . ") values (" . fields_insert("insert", $db_array, "") . " )";
             
     if (debug()) print "<br>DEBUG:page,db_update_or_create,insert: " . $query . "<br>\n"; 
     if (! (@mysqli_query ($connection, $query) ))
       dbshowerror($connection, "die");
   }
   
   return;
}



//
// function: db_update_or_create_if_new($tablename, $table_identifiers, $db_array)
//   $tablename - table name in compsys for which array applies
//   $table_identifies - formatted array of key/value pairs where the 
//        key is the db column name that is part of the identifier
//        (and thus the where clause), and the value is the data.
//   $tba_data_array - tBA data, formatted as an array of key/value 
//        pairs where the key is the db column name and the value
//        is the data.
//
// Updates database with data
//   Queries for row with data.  If found, updates only the columns which are 
//     currently NULL.  If not found, inserts.
//

function db_update_or_create_if_new($tablename, $table_identifiers, $db_array)
{
   global $connection;
   
   // set where clauase
   $where = fields_insert("where", $table_identifiers, "");
   
   // select to see if update or insert
   $query = "select * from {$tablename} where " . $where;
   if (debug()) print "<br>DEBUG:page,db_update_or_create_if_new, Select query: " . $query . "<br>\n";
   
   if (! ($result = @ mysqli_query ($connection, $query)))
  	  dbshowerror($connection, "die");
  	  
   // if return, update, otherwise insert
   if ($row = mysqli_fetch_array($result))
   {
     // walk through array and build array of values not set
     $new_dbarray = array();
     foreach($db_array as $col=>$val)
       if ($row[$col] == NULL) $new_dbarray = array_merge($new_dbarray, array($col=>$val));
       
     // form query with subset of fields
     $query = "update {$tablename} set " . fields_insert("update", $new_dbarray, "")
            .  " where " . $where;
     if (debug()) print "<br>DEBUG:page,db_update_or_create_if_new, update :" . $query . "<br>\n";
     if (! (@mysqli_query ($connection, $query) ))
       dbshowerror($connection, "die");
   } 
   else
   {
     $query = "insert into {$tablename} (" . fields_insert("nameonly", $db_array, "")
             . ") values (" . fields_insert("insert", $db_array, "") . " )";
             
     if (debug()) print "<br>DEBUG:page,db_update_or_create_if_new, insert: " . $query . "<br>\n"; 
     if (! (@mysqli_query ($connection, $query) ))
       dbshowerror($connection, "die");
   }
   
   return;
}


//
// ****************************************************************************
//
// Page display functions
//

// 
// function: showerror($text)
//  $text - text to show as error message
//
//  Displays error in red to the user.  Instructs user to use BACK button, and provides a link to the home page.
// 

function showerror ($text)
{
  global $base;
  print "<h2><font color=\"red\">{$text}</font></h2>\nPlease use BACK button on browser to correct and resubmit.\n"
  . "<br><br><a href=\"{$base}\">Return to Home Page</a><br><br>";
}

//
// function: pheader
//  $title - title to appear in html title
//  $titleonly = text flag that makes the title only visible in the title bar when set to "titleonly"
//      May be DEPRICATED
//  $options
//    - array of options
//    - openhead - leave head open  
//
//  Intended to be called from every page.  Sets up $user, starts HTML document.
//
//   set up HTML header with title and header properties
//   set H2 title in page as header info
//
// Note: = NUll is used as a default when called with fewer vars

function pheader($title, $titleonly = NULL, $options = NULL)
{
  global $user;
  global $admins;
  global $admin;
  global $event_id;
  global $font_sys_default;
  
  // get user if exists, then set admin privledge
  if (isset($_SERVER["PHP_AUTH_USER"]))
  {
  	$user = $_SERVER["PHP_AUTH_USER"];
  	
  	// check for admin rights
  	$index = array_search($user, $admins);
  	if (! ($index === FALSE)) $admin=TRUE; else $admin=FALSE;
  	
  }

  // format page header
  print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
                      \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
  print "\n<HTML>\n<HEAD><TITLE>{$title}</TITLE>\n";
  print "<style type=\"text/css\">
<!--
.style1 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: medium;
}
-->
</style>
";

  // if not "openhead"
  if (! (isset ($options["openhead"]))) print "</HEAD>\n<BODY class=\"style1\">\n";
  
  // add global font if set
  if ((isset($font_sys_default)) && ($font_sys_default != NULL)) print "<font face=\"arial\">\n";
  
  // if not titleonly
  if (! (isset ($titleonly)) || (! ($titleonly == "titleonly" )))
  	print "<h2>{$title}</h2>";
  		
}


//
// function: pfooter()
//
//   set up HTML footer, close db connection and commit trasactions
//

function pfooter()
{
  global $user;		// user id
  global $admin;	// admin rights
  global $connection;   // database connection
  global $host_team_name;  	// team host name
  global $sys_event_name;       // event name
  global $site;         // site in site URL
  global $base;         // base in site URL
  global $font_sys_default;
  
  print "<br><br><hr>" 
    . "<a href=\"{$base}\">Competition System Home</a><br>";
    
  // show user if exists
  if ($user)
  {
  	print "Logged in as {$user} ";
  	if ($admin) print " -- administrator";
  	print "\n&nbsp;&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  \n";
        print "<a href=\"http://logout@{$site}/\">Logout</a>";
  	print "<br>\n";
  }
  
  // add global font if set
  if ((isset($font_sys_default)) && ($font_sys_default != NULL)) print "</font>\n";
  
  print "<i>{$host_team_name} -- {$sys_event_name}</i>\n</BODY>\n</HTML>";
  
  // if database connection, commit and close down
  if ($connection)
  {
    // commit any transactions
    if (! (@mysqli_commit ($connection) ))
	dbshowerror($connection, "die");
    // close database connection
    mysqli_close($connection);
  }
}


//
// function: teamhref($teamnum)
//  $teamnum - number of team
//  
// Returns: href text to teaminfo page
//
// convenience function to return team <a href BLAH> on to teaminfo page.  
//  Does not supply the closing </a>.
// 
//

function teamhref ( $teamnum)
{
  return ("<a href=\"/teaminfo.php?teamnum={$teamnum}\">");
}

//
// function: tabtextfield($edit, $options, $data, $fieldname, $fieldtag, $size, $maxlenth, $defvalue, $format, $list_of_vals, $editprefix)
//  (function table text field)
//  $edit - edit flag, 1 if editing
//  $options - options to add (see below)
//  $data - dataarray with associative element named fieldname
//  $fieldname - name of field (in associative element, in forms, etc.)
//  $fieldtag - tag to display by field.  if "DisplayField", display field translation is referenced
//  $size - size of editing text box
//  $maxlength - max length of input allowed
//  $defvalue - value to place in text box if in editing mode.  Otherwise this value comes from $data
//  $format - printf-style format for number rendering (usually something for real types such as %.2f
//  $list_of_values - array of possible values, used in list_of_values selector when editing
//  $editprefix - placed on the front of every edit field so that multiple rows can be processed 
//                  if using tabular entry
//   
//
// Displays for reading or for edit in two <td> boxes.  Intended as a generic method to layout boxes
//  within layout tables.
//
// Options in options array:
//  ["tr"] = TRUE - add beginning and ending <tr> tags
//  ["notag="] = TRUE - do not print field label/tag.  Just the field itself
//  ["notd"] = TRUE - do not print <td> open and close
//  ["href"] = TRUE or data_value - enclose data value in href tag.
//               if set to TRUE, encloses the value in an href tag, starting with http://
//               if set to a data value, uses that in a href tag not starting with anything
//  ["th"] = TRUE - use <th></th> tags instead of <td> throughout
//  ["header"] = TRUE - use tag within <th> line without any data.
//   
//

function tabtextfield
  ( $edit, $options, $data, $fieldname, $fieldtag, $size, $maxlength = NULL, $defvalue = NULL, 
    $format = NULL, $list_of_vals = NULL, $editprefix = NULL )
{
  // set null vars
  $beg_tr = NULL; $beg_td = NULL; $beg_href = NULL; $end_href = NULL; $end_td = NULL; $end_tr = NULL; $tag = NULL;
  $value = NULL;
  
  // set up options for inclusion / exclusion
    // tr
    if ((isset($options["tr"])) && ($options["tr"] !== FALSE)) {$beg_tr="<tr valign=\"top\">\n"; $end_tr="</tr>"; }
    //
    // no td -- TRUE means don't include <td> tags.  Check if we're using th instead of td
    if ((! isset($options["notd"])) || ($options["notd"] !== TRUE)) 
    {
      // check if <th> instead of <td>
       if ((isset($options["th"])) && ($options["th"] !== FALSE)) 
       	 {$beg_td="<th>"; $end_td="</th>"; }       
       else
         {$beg_td="<td>"; $end_td="</td>"; }
    }
    //
    // notag -- don't include tag
    if ((! isset($options["notag"])) || ($options["notag"] !== TRUE)) $tag = "{$beg_td}{$fieldtag}{$end_td}";
    
    // look for value in data
    if (isset($data[$fieldname])) $value = $data[$fieldname]; else $value=$defvalue;
    
    // apply format if it exists
    if (isset($format)) $value = sprintf($format, $value);

    // set up href option
    if (isset($options["href"]))
    	{
    	  // if href is true, use value, otherwise use the href tag itself
    	  if ($options["href"] === TRUE)
            // strip off http start if there is one  
      	    if ( ! ($web = (strstr($value, 'http://'))) === FALSE)
	      $beg_href = "<a href=\"http://" . substr($web, 7) . "\">";    
	    else
              $beg_href = "<a href=\"http://{$value}\">" ; 	
          else // href tag is it's own value
	    $beg_href = "<a href=\"{$options['href']}\">" ; 
	     
	  $end_href = "</a>";
	}
   
    
    // set match length
    if (! isset($maxlength)) $maxlength = $size;
    
  // if in header mode, just print <th>$tag</th>, otherwise set up fields
  if ((isset($options["header"])) && ($options["header"] !== FALSE))
    $ret="{$beg_tr}{$beg_href}{$tag}{$end_href}\n{$end_tr}";
  else
    // set up edit and non-edit mode  
    if ($edit)
    {
      $ret = "{$beg_tr}{$tag}{$beg_td}";
      
      // if list of values, process
      //   note: could set up LOV to be $val=>$display
      if (isset($list_of_vals) && is_array($list_of_vals))
      {
        $ret .= "<select name= \"{$editprefix}{$fieldname}\">";
        foreach ($list_of_vals as $val)
        {
          if ($val == $value) $selected="selected"; else $selected="";
          $ret .= "\n   <option value=\"{$val}\" {$selected}>{$val}</option>";
        }
        $ret .= "</select>";
      }
      else
        $ret .= "<input type=\"text\" name= \"{$editprefix}{$fieldname}\" size={$size} maxlength={$maxlength} value=\"{$value}\">";
        
      $ret .= "{$end_td}{$end_tr}\n";
    }
    else  
      $ret="{$beg_tr}{$tag}{$beg_td}{$beg_href}{$value}{$end_href}{$end_td}{$end_tr}\n";
   
   return($ret);
}


//
// function: tabtextarea ($edit, $options, $data, $fieldname, $fieldtag, $row, $col, $defvalue, $list__of_vals, $editprefix  )
//  $edit - edit flag, 1 if editing
//  $options - options to add (see below)
//  $data - dataarray with associative element named fieldname
//  $fieldname - name of field (in associative element, in forms, etc.
//  $fieldtag - tag to display by field.  if "DisplayField", display field translation is referenced
//  $row - rows of text box when editing
//  $col - cols of text box when editing
//  $defvalue - value to place in text box if in editing mode.  Otherwise this value comes from $data
//
// Similar to tabtextfield, lays out display or edit box
//
// Options in options array:
//  ["tr"] = TRUE - add beginning and ending <tr> tags
//  ["notd"] = TRUE - don't use <td> tags to seperate values
//  ["notag="] = TRUE - do not print field label/tag.  Just the field itself
//  ["href"] = TRUE or data_value - enclose data value in href tag.
//               if set to TRUE, encloses the value in an href tag, starting with http://
//               if set to a data value, uses that in a href tag not starting with anything
//  ["th"] = TRUE - use <th></th> tags instead of <td> throughout
//  ["header"] = TRUE - use tag within <th> line without any data.
//  ["notagabove"] = TRUE - usually tabtextfield puts the description tag above the textbox 
//                    for rendering and editing.  If this option is set true, no setting above
//                    occurs.  No <tr> tags are used to create a "description on one row and 
//                    and edit box on the next.
//   
//

function tabtextarea
   ( $edit, $options, $data, $fieldname, $fieldtag, $row, $col, $defvalue = NULL, $list__of_vals = NULL, $editprefix = NULL  )
{
  // set null vars
  $beg_tr = NULL; $beg_td = NULL; $beg_href = NULL; $end_href = NULL; $end_td = NULL; $end_tr = NULL; $tag = NULL;
  $value = NULL;
  
  // set up options for inclusion / exclusion
    // tr
    if ((isset($options["tr"])) && ($options["tr"] !== FALSE)) {$beg_tr="<tr valign=\"top\">\n"; $end_tr="</tr>"; }
    //
    // no td -- TRUE means don't include <td> tags.  Check if we're using th instead of td
    if ((! isset($options["notd"])) || ($options["notd"] !== TRUE)) 
    {
      // check if <th> instead of <td>
       if ((isset($options["th"])) && ($options["th"] !== FALSE)) 
       	 {$beg_td="<th>"; $end_td="</th>"; }       
       else
         {$beg_td="<td>"; $end_td="</td>"; }
    }
    //
    // notag -- don't include tag
    $tag="";
    if ((! isset($options["notag"])) || ($options["notag"] !== TRUE))
      // look for "notagabove" - if set, do not underline or put above
      if (isset($options["notagabove"]) && ($options["notagabove"] !== FALSE))
        // non- above tag
        $tag = "{$beg_td}{$fieldtag}{$end_td}";
      else
        // "above style" tag
        $tag = "<tr>{$beg_td}<u>{$fieldtag}</u>{$beg_td}</tr>\n";
 
    // look for value in data
    if (isset($data[$fieldname])) $value = $data[$fieldname]; else $value=$defvalue;

    // set up href option
    if (isset($options["href"]))
    	{
    	  // if href is true, use value, otherwise use the href tag itself
    	  if ($options["href"] === TRUE)
            // strip off http start if there is one  
      	    if ( ! ($web = (strstr($value, 'http://'))) === FALSE)
	      $beg_href = "<a href=\"http://" . substr($web, 7) . "\">";    
	    else
              $beg_href = "<a href=\"http://{$value}\">" ; 	
          else // href tag is it's own value
	    $beg_href = "<a href=\"{$options['href']}\">" ; 
	     
	  $end_href = "</a>";
	}
   
  // if notagabove, treat like other edit boxes.  Otherwise start table to separate entry area
  if ((isset($options["notagabove"])) && ($options["notagabove"] !== FALSE))
    $ret="{$beg_tr}{$tag}{$beg_td}";
  else
    // start table
    $ret="{$beg_tr}{$beg_td}<table>{$tag}<tr><td>";
      
  // set up edit and non-edit mode  
  if ($edit)
    $ret .= "<textarea name=\"{$fieldname}\" rows=\"{$row}\" cols=\"{$col}\">{$value}</textarea>";
  else  
    $ret .= "{$beg_href}{$value}{$end_href}\n";
        

  // if notagabove, treat like other edit boxes.  Otherwise finish table
  if ((isset($options["notagabove"])) && ($options["notagabove"] !== FALSE))
    $ret .= "{$end_td}\n{$end_tr}";
  else
    // finish table
    $ret .= "</td></tr></table>{$end_td}{$end_tr}\n";
    
    
  return($ret);
}

//
// function: texttfield ($fieldtag, $fieldname, $size, $maxlength, $value )
//   $fieldtag - user-displayed tag in front of field
//   $fieldname - name used for variables and db field
//   $size - size of edit text box
//   $maxlength - maximum length of input allowed
//   $value - value placed in field
//

function texttfield( $fieldtag, $fieldname, $size, $maxlength, $value )
{
  if (! $maxlength) $maxlength = $size;
  // check for value existance and transform into string
  if ( $value ) $value = "value=\"{$value}\"";
  
  print "<tr><td>{$fieldtag}</td><td><input type=\"text\" name=\"{$fieldname}\" size={$size} maxlength={$maxlength} {$value}></td></tr>\n";
}

//
// fucntion: hiddenfield ( $fieldname, $value )
//   $fieldname - name used for variables and db field
//   $value - value placed in field
//
//  Used to place a hidden field within a form.
//

function hiddenfield( $fieldname, $value )
{
  // check for value existance and transform into string
  if ( $value ) $value = "value=\"{$value}\"";
  
  print "\n<input type=\"hidden\" name=\"{$fieldname}\" {$value}>\n";
}


//
// alliancefield ($edit, $color, $list_of_values)
//   $edit - edit mode
//   $color - (Red / Blue) designating color of alliance
//   $list_of_values - possible values for field
//
//  paints 3 text boxes for fields
//  
//

function alliancefield ( $edit, $color, $list_of_vals = NULL )
{
  $teamcnt = 0; // initialize 
  
  while ( $teamcnt++ < 3 )
  if ($edit)
    // print edit mode  
    if (isset($list_of_vals) && is_array($list_of_vals))
    {
      print "\n<select name=\"team_{$color}_{$teamcnt}\" size=1 maxlength=4>\n";
      foreach($list_of_vals as $val)
        print "<option value=\"{$val}\">{$val}</option>\n";
      print "</select>\n";
    }
    else
      //print "\n<input type=\"text\" name=\"team_{$color}_{$teamcnt}\" size=4 maxlength=4>\n";
      print "foo";
      
  else // not in edit mode
    print "\n";
      
}



//
// ****************************************************************************
//
// fields functions
//
//


//
// function: fields_load: ($post, $fieldnames, $editprefix)
//  loads fields names from post operation
//   $post = text value for get or post.  If set to "post" otherwise assumes get
//   $fieldnames = array of field names assumed to match html form field names
//   $editprefix = prefix appended to each fieldname for searching $_POST.  The 
//                  prefix allows easy processing of tabular data. See tabtextfields for use.
//
// Loads fieldnames from $GET or $POST.  If $post is set to "post", uses a $post variable.
//

function fields_load ( $post, $fieldnames, $editprefix = NULL )
{
   // iterate through field names
   foreach ($fieldnames as $fieldname)
    {
      // assign name a value
      if ( $post == "post" )
        // if $editprefix is used, accommodate
	if ($editprefix != NULL)
	  if (isset($_POST[$editprefix . $fieldname])) $formfields[$fieldname] = $_POST[$editprefix . $fieldname];
  	  else
            $formfields[$fieldname] = NULL;
        else
	  if (isset($_POST[$fieldname])) $formfields[$fieldname] = $_POST[$fieldname];
  	  else 
            $formfields[$fieldname] = NULL;
      else // not "post"
      	if (isset($_GET[$fieldname]))
          $formfields[$fieldname] = $_GET[$fieldname];
        else
          $formfields[$fieldname] = NULL;
    }
    
    return ($formfields);
}



//
// function: fields_insert ( $operation, $formfields, $fieldnames )
//  fields_insert: create insert string
//  $operation - name, value, where
//        nameonly or fieldname - name of fields, comma separated
//        insert - values used in SQL insert statement
//        where - where-clause name=value, and separate
//        update - update clause
//  $formfields - array created in fields_load of each field 
//      (an ordered associative array of name=>value for each field)
//  $fieldnames - optional array of field names to be inserted 
//      (an ordered array of db column fieldnames)
//
//   Returns string for insertion into SQL statement.
//
//  Creates strings for insertion into SQL statements based on $operation.
//   Strings include column names (fieldname or nameonly),
//   insert values ('value','value'...), or update/where statements (field='value')
//
//

function fields_insert ( $operation, $formfields, $fieldnames = NULL )
{
   $insert="";

   // if field names, use the array that is passed, otherwise iterate over formfields
   if ($fieldnames)
     foreach($fieldnames as $fieldname)
	$insert = fields_insert_add ( $operation, $formfields, $fieldname, $insert);
   else
     // iterate through fields and create insert string
     foreach ($formfields as $fieldname => $value)
	$insert = fields_insert_add ( $operation, $formfields, $fieldname, $insert);
   
   return ($insert);
}

 // function used in fields_value
function fields_insert_add ( $operation, $formfields, $fieldname, $insert)
  {
  	// get db connection
  	global $connection;
  	global $db_case_sensitive;
  	      	
      	switch ($operation)
      	{
      	  case "nameonly":
      	  case "fieldname":
      	    	// add comma
      	        if ( $insert ) $insert = $insert . ", ";
      
      	  	$insert = $insert . $fieldname;
      	  	break;
      	  	
      	  case "insert":
      	  	// clean sql inputs before submitting -- not sure why this is commented out -- jlv
      	  	// $formfields[$fieldname] = mysqli_real_escape_string($connection, $formfields[$fieldname]);
      		// clean sql inputs before submitting
      	  	// $formfields[$fieldname] = mysqli_real_escape_string($connection, $formfields[$fieldname]);
      	  	
      	    	// add comma
      	        if ( $insert ) $insert .= ", ";
      	  	
      		// add insert value
      		if ( $formfields[$fieldname] === "" ) 
        		$insert .= "NULL";
      		else
      		{
      			// clean sql inputs before submitting
      			$formfields[$fieldname] = mysqli_real_escape_string($connection, $formfields[$fieldname]);
      			
        		$insert .= "'$formfields[$fieldname]'";
        	}
        	break;
        	
          case "update":
      		// clean sql inputs before submitting
      	  	$formfields[$fieldname] = mysqli_real_escape_string($connection, $formfields[$fieldname]);

      	    	// add comma
      	        if ( $insert ) $insert .= ", ";
      	        
      	  	// add insert value
      		if ( $formfields[$fieldname] === "" ) 
        		$insert .= "$fieldname = NULL";
      		else
        		$insert .= "$fieldname = '$formfields[$fieldname]'"; 
        	break;
        	
          case "where":
                // clean sql inputs before submitting
      	  	$formfields[$fieldname] = mysqli_real_escape_string($connection, $formfields[$fieldname]);

      	    	// add comma
      	        if ( $insert ) $insert .= " and ";
      	        
      	  	// add where value
      		if ( $formfields[$fieldname] === "" ) 
        		$insert .= "$fieldname = NULL";
      		else
        		$insert .= "$fieldname {$db_case_sensitive} = '$formfields[$fieldname]'"; 
        	break;
      	  	
          default:
          	showerror("Code error: No operation value assigned in fields_insert_add.");
	}

    return ($insert);
}


//
//
// ****************************************************************************
//
// parameters handling functions
//
//    functions that use and manipulate the configurable variables set up 
//     in params.inc
//
//

// 
// function: param_array ($paramgroup)
//  creates array of needed form fields
//   $paramgroup - group name for paramters (Play, Match, etc.)
//   Retuns an array of valid fieldnames
//

function param_array ($paramgroup)
{
   global $dispfields;
   
   // initialize
   $fcnt=0;
   $fieldnames = array();
   
   foreach($dispfields[$paramgroup] as $key)
     if ($key['used'] == TRUE)
//DBCOL     	$fieldnames[] = $paramgroup . "Field_" . $fcnt++;
     	$fieldnames[] = $key['tag'];   

   return($fieldnames);
}

//
//
// function: tabparamfields($edit, $options, $data, $paramgroup);
//   $edit - edit option
//   $options - identical to tabtextfield options (passed to tabtextfield)
//      Addtional options for this function:
//         pagebreak = if non-zero (set), break page for these number of columns (2 or more)
//         pagebreakstring = string inserted to cause pagebreak.  Works
//           in tandem with pagebreak
//   $data - data row with field params (similar to tabtextfield)
//   $paramgroup - group name for paramters (Play, Match, etc.)
//
//   Displays param fields in a table form, similar to texttabfield.  Displays all param fields
//    in a paramgroup
//

function tabparamfields($edit, $options, $data, $paramgroup)
{
 global $dispfields;

 // initialize vars
 $ret = NULL;
   
 //
 // calculate pagebreak rows if needed
 //
 //  Note: pagebreak rows may not be the same and $cnt because used==FALSE rows shouldn't be counted
 //
 if ((isset ($options["pagebreak"])) && ($options["pagebreak"] !== FALSE))  // if set
 {
	// set flag
	$pagebreakrow=1;
     	if ($options["pagebreak"])			// if non-zero
	{
	   // loop through to get total
	   $cnt=0;
	   $rowtot=0;
	   while ( isset($dispfields[$paramgroup][$cnt]) && ( $cnt < 50 ) )
	   {
		if ($dispfields[$paramgroup][$cnt]["used"]===TRUE)
			$rowtot = ++$rowtot;
		$cnt = ++$cnt;
	   }
	   
	   // calculate pagebreak row
	   $pagebreakrow = ceil ($rowtot / $options["pagebreak"]);
	}
  }
  else $pagebreakrow=0;
	
   // loop while var is set.  Don't go over 50 as a safety check
   $cnt=0;
   $rowcnt=1;

   while ( isset($dispfields[$paramgroup][$cnt]) && ( $cnt < 50 ) )
   {
   	// if param is used place in group
   	if ($dispfields[$paramgroup][$cnt]["used"]===TRUE)
   	{
//DBCOL		$ret = $ret . "\n" . tabtextfield ( $edit, $options, $data, $paramgroup . "Field_" . $cnt, 
		$ret = $ret . "\n" . tabtextfield ( $edit, $options, $data, $dispfields[$paramgroup][$cnt]['tag'], 
			$dispfields[$paramgroup][$cnt]["display"],
			$dispfields[$paramgroup][$cnt]["inputlen"], $dispfields[$paramgroup][$cnt]["maxlen"],
			$dispfields[$paramgroup][$cnt]["default_value"]);
		
		 // pagebreak if needed
		 if ($pagebreakrow)				// if pagebreaking
		     if (! ($rowcnt++  % $pagebreakrow  ))			// if zero
			   $ret = $ret . $options["pagebreakstring"];
	}
	$cnt = ++$cnt;


   }
   
   return($ret);
      
}

//
//
// function: param_insert($operation, $formfields, $paramgroup)
//  display/edit custom parameterized fields
//
//  NOTE: operands similar to fields_insert
//  $operation - name, value, where
//        nameonly or fieldname - name of fields, comma separated
//        insert - values used in SQL insert statement
//        where - where-clear name=value, comma separated
//  $formfields - array created in fields_load of each field 
//      (an ordered associative array of name=>value for each field)
//  $paramgroup - group name for paramters (Play, Match, etc.)
//
//   Adds SQL statement fields to SQL statements, similar to fields_insert.
//   Creates strings for insertion into SQL statements.  Strings include column names (fieldname or nameonly),
//   insert values ('value','value'...), or update/where statements (field='value')
//

function param_insert( $operation, $formfields, $paramgroup)
{
   global $dispfields;

   $cnt=0;
   
   // loop while var is set.  Don't go over 50 as a safety check
   while ( isset($dispfields[$paramgroup][$cnt]) && ( $cnt < 50 ) )
   {
   	if ($dispfields[$paramgroup][$cnt]["used"]===TRUE)
//DBCOL		$fieldnames[$cnt] = $paramgroup . "Field_" . $cnt;
		$fieldnames[$cnt] = $dispfields[$paramgroup][$cnt]["tag"];
	$cnt = ++$cnt;
   }
   
   // call field insert   
   return(fields_insert ( $operation, $formfields, $fieldnames ));
      
}



//
// function: param_load ($post, $paramgroup)
//  loads paramterized fields names from post operation
//   $post = text value for get or post
//   $paramgroup - group name for paramters (Play, Match, etc.)
//
//  Loads parameter fields from $GET or $POST vars.
//
function param_load( $post, $paramgroup)
{
   global $dispfields;

   $cnt=0;
   
   // loop while var is set.  Don't go over 50 as a safety check
   while ( isset($dispfields[$paramgroup][$cnt]) && ( $cnt < 50 ) )
   {
	if ($dispfields[$paramgroup][$cnt]["used"]===TRUE)
//DBCOL		$fieldnames[$cnt] = $paramgroup . "Field_" . $cnt;
		$fieldnames[$cnt] = $dispfields[$paramgroup][$cnt]["tag"];
	$cnt = ++$cnt;
   }
   
   // call field insert   
   return(fields_load ( $post, $fieldnames ));      
}

//
// function:param_report ($data, $paramgroup, $public))
//   $data - array or field=>value row arrays (multiple teams)
//   $paramgroup - group name for paramters (Play, Match, etc.)
//   $public - if set to 1 then only print competitive info
//   $teamcnt - count of total teams in paramter report
//
//   Returns a string of table rows
//
// Builds team comparison rows
// 
//

function param_report ($data, $paramgroup, $public, $teamcnt)
{
   global $dispfields;
   $ret = "";
   $cnt=0;
   
   // loop while var is set.  Don't go over 50 as a safety check
   while ( isset($dispfields[$paramgroup][$cnt]) && ($dispfields[$paramgroup][$cnt]["used"]===TRUE) && ( $cnt < 50 ) )
   {
	if ($dispfields[$paramgroup][$cnt]["used"]===TRUE)
	{
		// find field name
//DBCOL		$fieldname = $paramgroup . "Field_" . $cnt; 
		$fieldname = $dispfields[$paramgroup][$cnt]["tag"]; 

		// start row
		$ret = $ret . "<tr><td>{$dispfields[$paramgroup][$cnt]["display"]}</td>\n";

		// loop through each team array
		// if public, only print first 3, not competition
		if ($public) $tot=3; else $tot=$teamcnt;
		for($i=0; $i<$tot; $i++)
			$ret = $ret . "<td>{$data[$i][$fieldname]}</td>";

		 //end row
		$ret = $ret . "</tr>\n";
	}

	// bump counter
	$cnt = ++$cnt;
   }
   
   return ($ret);
}


//
// ****************************************************************************
//
// system functions
//

// function get_system_value($name)
//  $name - name of sytem key (value in db)
//
// returns value or NULL if not set
//
// gets system key->value from database system_value table

function get_system_value($name)
{
   global $connection;
   
   // select to see if update or insert
   $query = "select value from system_value where skey = '{$name}'";
   if (debug()) print "<br>DEBUG:page,get_system_value: " . $query . "<br>\n";
   
   if (! ($result = @ mysqli_query ($connection, $query)))
  	  dbshowerror($connection, "die");
  	  
   // if return, update, otherwise insert
   if ($row = mysqli_fetch_array($result))
     return($row['value']);
   else
     return(NULL);
}


// function set_system_value($name)
//  $name - name of sytem key (value in db)
//  $value - value to set
//
// returns TRUE if able to set
//
// gets system key->value from database system_value table

function set_system_value($name, $value)
{
   global $connection;
   
   // select to see if update or insert
   $query = "select value from system_value where skey = '{$name}'";
   if (debug()) print "<br>DEBUG:page,set_system_value, Select: " . $query . "<br>\n";
   
   if (! ($result = @ mysqli_query ($connection, $query)))
  	  dbshowerror($connection, "die");
  	  
   // if return, update, otherwise insert
   if ($row = mysqli_fetch_array($result))
   {
     $query = "update system_value set value = '{$value}' where skey = '{$name}'";
     if (debug()) print "<br>DEBUG:page,set_system_value,update:" . $query . "<br>\n";
     if (! (@mysqli_query ($connection, $query) ))
       dbshowerror($connection, "die");
   } 
   else
   {
     $query = "insert into system_value (skey, value) values ('{$name}', '{$value}')";
             
     if (debug()) print "<br>DEBUG:page,set_system_value,insert:" . $query . "<br>\n"; 
     if (! (@mysqli_query ($connection, $query) ))
       dbshowerror($connection, "die");
   }
   
   return(TRUE);
}


//
// user profile / use preferences test and set
//

// function test_user_prop ($property)
//  $property - property name, corresponding to column in user_profile
//
// returns 1, 0 otherwise
//

function test_user_prop ($property)
{
  global $connection;
  global $user;

  // query db
  $query = "select {$property} from user_profile where user = '{$user}'";

  if (debug()) print "<br>DEBUG:page,test_user_prop: " . $query . "<br>\n";
   
  if (! ($result = @mysqli_query ($connection, $query)))
 	  dbshowerror($connection, "die");
  	  
  // if return, update, otherwise insert
  if ($row = mysqli_fetch_array($result))
    if ($row[$property] == 1) return (1);
   
  // default return
  return(0);
}


// function set_user_prop ($property, $value)
//  $property - property name, corresponding to column in user_profile
//  $value - boolean 1/0 value to set
//
// returns TRUE if able to set
//

function set_user_prop ($property, $value)
{
  global $connection;
  global $user;

  // query db
  $query = "insert into user_profile (user, {$property}) values ('{$user}', {$value}) "
           . "on duplicate key update user = '{$user}', {$property} = {$value}";
  
  if (debug()) print "<br>DEBUG:page,set_user_prop: " . $query . "<br>\n";
   
  if (! ($result = @mysqli_query ($connection, $query)))
  	  dbshowerror($connection, "die");
  
  return;
}



//
// ****************************************************************************
//
// teams functions
//

//
// function: alliances_load ($post)
//  $post = value or get or post
// assumes team_color_cnt format on variable -- loads into teams arrays
//

function alliances_load( $post )
{
   // iterate through colors and team numbers
   foreach (array("Red", "Blue") as $color)
    {
      $teamcnt=0;
      while ($teamcnt++ < 3)
       // assign name a value
       if ( $post == "post" )
  	 $teams[$color][$teamcnt] = $_POST["team_{$color}_{$teamcnt}"];
       else
         $teams[$color][$teamcnt] = $_GET["team_{$color}_{$teamcnt}"];
     }
    return ($teams);   
}

  
// 
// function: teams_validate ( $teams )
//  $teams - array of teams loaded in alliances_load
//
//  Returns: FALSE if a team doesn't exist and shows error for each team.
//
//  Validates each team in array exists in teambot table.
//

function teams_validate ( $teams )
{
   // get database connection
   global $connection;
   global $sys_event_id;
   $error=0;
   
   // iterate through each team and check db for existance
   foreach ( $teams as $color =>$alliance )
   {
     foreach ( $alliance as $team )
     {
       // query db
       if (! ($results =  @mysqli_query ( $connection, "select teamnum from teambot where event_id = '{$sys_event_id}' and teamnum = {$team}")))
          dbshowerror("die");
       
       // if no result, team does not exist.
       if (! ($row = @mysqli_fetch_array($results)))
          {
            showerror("Team {$team} not found in teambot database for current event '{$sys_event_id}'");
            $error=1;
          }      
     }
    }
   if ($error) return(FALSE); else return(TRUE);
}



//
// teams need evaluation functions
//
//
// Basic use pattern:


// For one-shot team number display:
//  To display teamnum (with or without &bull)
//   team_needs_eval_str($teamnum) where teamnum is displayed
// 
// For lots of teams displayed on page:
//  At top of page, to get array:
//    $needseval = test_user_prop("needeval");
//    if ($needseval == 1) $teams_need_eval = allteams_need_eval(); else $teams_need_eval = array();
//
//  Inline in page where bullet would go:
//    if (in_array($row["teamnum"], $teams_need_eval)) print "&bull;";

// 
// function: team_needs_eval ( $teamnum )
//  $teamnum of team
//
//  Returns: TRUE if $teamnum needs evaluation, FALSE if not
//
//  Evaluation columns are defined in system global $needs_eval_columns
//  Needs eval means at least one of these columns is null
//

function team_needs_eval ($teamnum)
{
  global $connection;
  global $needs_eval_columns;
  global $sys_event_id;
  
  // create where clause array
  $colwhere = "";
  foreach($needs_eval_columns as $column)
  {
    if ($colwhere != "") $colwhere .= " or ";
    $colwhere .= $column . " is null ";
  }

  // query db
  $query = "select teamnum from teambot where event_id = '{$sys_event_id}' and teamnum = '{$teamnum}' and ({$colwhere})";

   if (debug()) print "<br>DEBUG:page,team_needs_eval: " . $query . "<br>\n";
   
   if (! ($result = @mysqli_query ($connection, $query)))
  	  dbshowerror($connection, "die");
  	  
   // if return, update, otherwise insert
   if ($row = mysqli_fetch_array($result)) return(TRUE); else return(FALSE);
}


// function: team_needs_eval_str ( $teamnum )
//  $teamnum of team
//
//  Returns: string with teamnum if not needing eval and teamnum with a bullet if
//   needing eval
//
//  Evaluation columns are defined in system global $needs_eval_columns
//  Needs eval means at least one of these columns is null
//

function team_needs_eval_str ($teamnum)
{
  if ((team_needs_eval($teamnum)) && (test_user_prop("needeval")))
    return($teamnum . "&bull;"); 
  else return($teamnum);
}

// function: allteams_need_eval ()
//
//  Returns: array of teamnums which need evaluation
//
//  Evaluation columns are defined in system global $needs_eval_not_null_cols.
//  Needs eval means at least one of these columns is null
//

function allteams_need_eval () : array
{
  global $connection;
  global $needs_eval_columns;
  global $sys_event_id;
  
  // create where clause array
  $colwhere = "";
  foreach($needs_eval_columns as $column)
  {
    if ($colwhere != "") $colwhere .= " or ";
    $colwhere .= $column . " is null ";
  }

  // declare array
  $teamnums = array();
  
  // query db
  $query = "select teamnum from teambot where event_id = '{$sys_event_id}' and ({$colwhere})";
  
   if (debug()) print "<br>DEBUG:page,allteams_need_eval: " . $query . "<br>\n";
  
   if (! ($result = @mysqli_query ($connection, $query)))
  	  dbshowerror($connection, "die");
  	  
   // if return, update, otherwise insert
   while ($row = mysqli_fetch_array($result))
     $teamnums[] = $row['teamnum'];

  return($teamnums);
}


//
// ********************
//
// match schedule functions
//
//

//
// match time functions
//

// function: match_get_act_est_time ($type, $matchnum, $actual_utime, $scheduled_utime)
//  $type - match type, P,Q,F
//  $matchnum 
//  $actual_utime
//  $scheduled_utime
//
//  Returns: $match_time_array of key/value pairs, with the following keys:
//    ['heading_tag']
//    ['display_time']
//    ['can_set']
//    ['est_flag']
//    ['estimated_utime']
//
//  Others we may implement:
//    ['seconds_delay']
//
// 
// if $actual_utime 
//  - is not null: converts to HH:MM can displays in display time. heading tag is Act
//  - is null, provides estimated time based on system value match_delay
//
// Uses two system values:
//   - match_time_delay - current delay time from scheduled in match timing
//   - match_time_date - date in YYYY-MM-DD format the last delay was calc'd.  If today is not 
//      same day, resets match_time_delay
//
//

function match_get_act_est_time ($type, $matchnum, $actual_utime, $scheduled_utime)
{
  global $connection;
  global $sys_event_id;
  global $schedule_match_window;
  
  $time_array = array();
  
  // if actual time is set, return actual date in format format
  if ($actual_utime != NULL)
  {
    $time_array['display_time'] = date('H:i',$actual_utime);
    $time_array['can_set'] = FALSE;
    $time_array['heading_tag'] = 'Act';
    $time_array['est_flag'] = FALSE;
  }
  else
  {
    // determine if match is before last actual time
    $recent_matchnum = match_get_recent_actual ($type);

    if ($matchnum <= $recent_matchnum)
    {
      $time_array['display_time'] = "";
      $time_array['can_set'] = FALSE;
      $time_array['heading_tag'] = 'Est';
      $time_array['est_flag'] = FALSE;
    }
    else
    {
      // Algorithm:
      //  Obtain delay from match_get_delay ($scheduled_utime)  
      //  set estimated_utime, set other variables.
      //  If current time is within $schedule_match_window of estimated_utime, set can_set flag
      //
      $delay = match_get_delay ($scheduled_utime);

      // set variables
      $time_array['estimated_utime'] = $scheduled_utime + $delay;
      $time_array['display_time'] = date('H:i',$time_array['estimated_utime']);
      $time_array['heading_tag'] = 'Est';
      $time_array['est_flag'] = TRUE;    
    
      // if time within time window, set can_set TRUE
      if ( abs(time() - $time_array['estimated_utime']) <= $schedule_match_window)
        $time_array['can_set'] = TRUE;
      else
        $time_array['can_set'] = FALSE;     
     } // end matchnum recent else
  }  // end actual_utime null else
    
  return($time_array);

}


// function: match_set_time ($type, $matchnum)
//  $type - match type, P,Q,F
//  $matchnum 
//
//  Returns: TRUE if set time, FALSE if match time was not set
//
//  Will directly print errors when time could not be set.  Sets actual time of match if within
//    $schedule_match_window and updates system delay variables as needed
//

function match_set_time ($type, $matchnum)
{
  global $connection;
  global $sys_event_id;
  global $schedule_match_window;
  
  
  // check if matchnum is less than most recent
  $recent_matchnum = match_get_recent_actual ($type);    
  if ($matchnum <= $recent_matchnum) return(FALSE);
  
  // get match scheduled and estimated time.  If actual time set, abort.
  $query="select scheduled_utime, actual_utime from match_instance " 
          . "where event_id = '{$sys_event_id}' and type = '{$type}' and matchnum = '{$matchnum}'";
  if (debug()) print "<br>DEBUG:page, set_match_time:" . $query . "<br>\n";
  if (! ($result = @mysqli_query ($connection, $query) ))
  	dbshowerror($connection, "die");
  $row = mysqli_fetch_array($result);  
 
  // check if actual time is set
  if ($row['actual_utime'] != NULL) return(FALSE);
  
  //
  // check if we can set, then set actual time and set new delay time
  //
  
  // get current delay
  $delay = match_get_delay ($row['scheduled_utime']);

  // if time not within time window, return false, otherwise set time
  $now = time();
  if ( abs($now - ($row['scheduled_utime'] + $delay)) > $schedule_match_window)
    return (FALSE);
  else
  {
    // set delay values
    set_system_value("match_time_date", date('Y-m-d', $now));
    set_system_value("match_time_delay", $now - $row['scheduled_utime']);

    // set match actual time
    $query="update match_instance set actual_utime = {$now} " 
            . "where event_id = '{$sys_event_id}' and type = '{$type}' and matchnum = '{$matchnum}'";
    if (debug()) print "<br>DEBUG:page, set_match_time:" . $query . "<br>\n";
    if (! ($result = @mysqli_query ($connection, $query) ))
  	dbshowerror($connection, "die");

    return (TRUE); 
  }
}
      

// function match_get_delay ($scheduled_utime)
//  $scheduled_utime - scheduled utime for comparison
//
//  Returns: seconds of delay to apply to match
//
// Determines if delay time applies to $scheduled_utime and returns delay
//
// Uses two system values:
//   - match_time_delay - current delay time from scheduled in match timing
//   - match_time_date - date in YYYY-MM-DD format the last delay was calc'd.  If today is not 
//      same day, resets match_time_delay
//

function match_get_delay ($scheduled_utime)
{
  global $schedule_match_window;
  
  // Algorithm:
  //  Obtain date. If date has changed, current delay is not valid. Null 
  //  out delay and date and report null.  
  //  If date and delay are set, and today is the match time, set delay
  //
  $date = get_system_value("match_time_date");
  // assume $delay=0 and set otherwise
  $delay = 0; 
  // if date not null, then check date
  if ($date != NULL)
    // check dates match today
    if ($date != date('Y-m-d'))
    {
      // set to delay values to null
      set_system_value("match_time_date", NULL);
      set_system_value("match_time_delay", NULL);
    }
    else if (date('Y-m-d', $scheduled_utime) == date('Y-m-d'))
      $delay = get_system_value("match_time_delay");

  return($delay);
}


//
// function: match_get_recent_actual ($type)
//  $type - match type, P,Q,F
//
//  Returns: $matchnum of most recent actual time that has been set, or FALSE

function match_get_recent_actual ($type)
{
  global $sys_event_id;
  global $connection;
  
  $query="select matchnum from match_instance " 
          . "where event_id = '{$sys_event_id}' and type = '{$type}' "
          . "and actual_utime is not NULL order by matchnum DESC";
  if (debug()) print "<br>DEBUG:page, match_get_recent_actual:" . $query . "<br>\n";
  if (! ($result = @mysqli_query ($connection, $query) ))
  	dbshowerror($connection, "die");

  // if result, send in return
  if ($row = mysqli_fetch_array($result))
    return ($row['matchnum']);
  else
    return (0);
}



//
// ****************************************************************************
//
// end of page.inc -- do not leave any lines under the end of this closure
?>