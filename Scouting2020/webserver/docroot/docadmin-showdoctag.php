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

// check post then get for docnode:
if(isset($_POST["docnode"])) $docnode=$_POST["docnode"];
else if(isset($_GET["docnode"])) $docnode=$_GET["docnode"];
else $docnode="";

// header and setup
pheader("Documentation Admin: Show Documents");
$connection = dbsetup();


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
  $query = "select docnode from docnode where doctag is not null order by docnode";
  if (debug()) print "<br>DEBUG-{$pagename}:" . $query . "<br>\n";
  if (!($result = @ mysqli_query ($connection, $query)))
    dbshowerror($connection);

  // get rows and build $doctags array
  while($row = mysqli_fetch_array($result))
	$docnodes_lov[] = $row['docnode'];

//
// top of form rendering
//


  	print "<form method=\"POST\" action=\"/{$pagename}.php?\"?docnode={$docnode}>\n";
  	print "Docnode: &nbsp; <select name=\"docnode\">\n";

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
  print "<a href=\"/admin.php\">Return to Admin</a>\n";
  print "&nbsp;&nbsp;&nbsp;<a href=\"/\">Return to Home</a><br><br>\n";

print "
<!----- Top of page ----->
<table valign=\"top\" border=\"2\">
"; // end of print

  //
  // create page
  //

  $url['toc']="/docadmin-showmenu.php?";
  // get string to print
  if (! $rs = doc_show_doc($docnode, $url))
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