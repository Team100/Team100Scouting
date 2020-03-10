<?php
  // $Revision: 2.1 $
  // $Date: 2010/04/22 04:00:55 $
  //
  // Competition System - Blue Alliance parameters load
  //
  // Provides a text-box method for copy/paste custom parameters
  //
  require "page.inc";

  // get variables, checking for existance
  if(isset($_GET["vargroup"])) $vargroup=$_GET["vargroup"]; else $vargroup="tBA";
  if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;

  // header and setup
  pheader("Custom Parameters - Blue Alliance Pasted Params Setup");
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
      if (isset($_POST["rawparams"]))
        {
		  // load rawparams into array
		  $paramlines = explode("\n",$_POST["rawparams"]);

		  // debug
		  if (debug()) { print "\n\nArray received:\n"; print_r($paramlines);}

          // loop through paramlines, exploding with tab for tBA type
          $cnt=0;
          foreach ($paramlines as $paramline)
          {

            $params = explode("\t",$paramline);

            if (debug()) { print "\n  Pair received:\n"; print_r($paramline);}

            $tba_tag = $params[0];

            $tba_type = rtrim($params[1]);

            // truncate tag at 20 chars
            $tag = substr("f_" . $tba_tag, 0, 20);

            // test tag and skip if needed
            if ($tag == "")
              // show user
              print "<br><b>A tag was NULL.  Skipping that tag.</b><br>\n";
            else
            {
              // message user
              print "Processing tBA tag {$tba_tag}<br>\n";
              // dbtype helper
              $dbtype = "varchar";  // default
              switch ($tba_type) {
                case "integer": $dbtype = "int"; break;
                case "boolean": $dbtype = "boolean"; break;
                case "real": $dbtype = "real"; break;
              }

              $col_len = 20; 	// default database column length

              // assign vars
              $custom_params = array("tag"=>$tag,"position"=>$cnt,"used"=>1,"vargroup"=>"tBA_match",
                 "entrytype"=>"R","dbtype"=>$dbtype,"display"=>$tag,"maxlen"=>$col_len,
                 "description"=>"Blue Alliance custom variable {$tba_tag}",
                 "tBA_tag"=>$tba_tag,"tBA_type"=>$tba_type);

              $cnt++;

              // set up query
              $query = "insert into custom_param (" . fields_insert("nameonly", $custom_params, "")
                   . ") values (" . fields_insert("insert", $custom_params, "") . " )";

		      // process query
		      if (debug()) print "<br>DEBUG-customparam-tBA-setup: " . $query . "<br>\n";
		      if (! (@mysqli_query ($connection, $query) ))
		    	    dbshowerror($connection, "die");

		     } // end else tag
          }   // end of foreach paramlines

	      // commit
		  if (! (@mysqli_commit($connection) ))
		   	dbshowerror($connection, "die");


	  } // if isset rawparms
	}  // isset op


    // update completed, instead of reset edit mode used edit mode 90 to paint results page
    $edit = 90;
   }


//
// paint results page, or paint entry page
//

if ($edit == 90)
print "
<br>
<br>
Tag processing completed.  Go to custom parameter editing to finish customization.
Check display names and descriptions.
<br>
<br>
<a href=\"/customparam.php?vargroup=tBA\">Go to Custom Parameter Entry<a>
<br>
<br>
";

else
{

//
// top of form rendering
//


//
// provide explanation
//
print "
This form provides a short-cut to load custom Blue Alliance parameters.  It sets up
<ol>
<li>Go to BlueAlliance.com API documentation page
( <a href=\"https://www.thebluealliance.com/apidocs/v3\">www.thebluealliance.com/apidocs/v3<a>)
<li>Look for the definition at the bottom of the page in the schema section, with a phrase
such as \"Match Score Breakdown_XXXX\".
<li>Select and copy the variables and types.
<li>Copy into notepad or another editor, and clean up any abnormalities.
<li>Your output should be of the form VariableName<tab>Type
<li>Paste into the text area below
</ol>
"; // end print



print "

<form method=\"POST\" action=\"/customparam-tBA-setup.php?edit=2\">



<!----- Top of page ----->
<table valign=\"top\">
<tr valign=\"top\">
<td>The Blue Alliance custom params -tab- type</td>
</tr>

<tr>
<td><textarea name=\"rawparams\" rows=\"40\" cols=\"70\"></textarea>
</td>
</tr>

<INPUT TYPE=\"submit\" name=\"op\" VALUE=\"Save\" ALIGN=middle BORDER=0>
&nbsp;<INPUT TYPE=\"submit\" name=\"op\" VALUE=\"Cancel\" ALIGN=middle BORDER=0>

<br><br><a href=\"/customparam.php?vargroup=tBA\">Return to Custom Parms</a>

"; // end of print

  // close page
  print "</td></tr></table>\n";
  print "</form>\n";

} // end of else from edit=90 or regular paint

  // return and home buttons
  print "<br><br><a href=\"/admin.php\">Return to Admin</a><br>\n";
  print "<a href=\"/\">Return to Home</a>\n";

  print"</tr></table>\n";

   pfooter();
  ?>