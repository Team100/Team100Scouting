<?php
// $Revision: 2.1 $
// $Date: 2010/04/22 04:00:55 $
//
// Competition System - Documentation admin - show menupage
//
// Node / tree management scren
//
require "page.inc";
$pagename = "docadmin-showdoc";

// get variables, checking for existance
if(isset($_GET["edit"])) $edit=$_GET["edit"]; else $edit=NULL;
if(isset($_GET["doc_op"])) $doc_op=$_GET["doc_op"]; else $doc_op=NULL;
$doc_options=array();

// check post then get for parent
if(isset($_POST["parent"])) $docnode=$_POST["parent"];
else if(isset($_GET["parent"])) $docnode=$_GET["parent"];
else $docnode="";

// header and setup
pheader("Documentation Admin: Menus");
$connection = dbsetup();

// check for docnode as well, then find parent if needed.
if(isset($_POST["docnode"])) $docnode=$_POST["docnode"];
else if(isset($_GET["docnode"])) $docnode=$_GET["docnode"];
else $docnode="";

// if docnode null, set to root
if ($docnode == "") $docnode = 'Root';

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


  	print "<form method=\"POST\" action=\"/{$pagename}.php?\"?docnode={$docnode}>\n";
  	print "Nodes under this parent: &nbsp; <select name=\"docnode\">\n";

    // loop through entries
    foreach($docnodes_lov as $tagselect)
    {
      if ($tagselect == $docnode) $selected="selected"; else $selected="";
      print "<option value=\"{$tagselect}\" {$selected}>{$tagselect}</option>\n";
    }
    print "</select>&nbsp;&nbsp;\n";

  	print "<input type=\"submit\" name=\"op\" VALUE=\"Submit\" ALIGN=middle BORDER=0>\n";
    print "</form>\n";
    print "<br>\n";



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

  // URL setup
  $url['toc']="/docadmin-showdoc.php?";
  $url['doctag'] = "/docadmin-showdoc.php?";
  // get string to print
  if (! $rs = doc_show_node($docnode, $url, $doc_options, $doc_op))
    print "<b>Error: documentation system could not find node '{$docnode}'.</b><br>\n";
  else
    print $rs;

  // end table
  print "</table><br>\n";


  // return and home buttons
  print "<br><br><a href=\"/admin.php\">Return to Admin</a><br>\n";
  print "<a href=\"/\">Return to Home</a>\n";

   pfooter();
  ?>