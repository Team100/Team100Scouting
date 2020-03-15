<?php
  // $Revision: 3.0 $
  // $Date: 2016/03/14 22:56:41 $
  //
  // Competition System
  // Blue Alliance Data Automatic Loader
  //
  // Runs in background periodically and updates data needed at tournament
  //
  // Assumes process running the script is pointing it to a log
  //

  // check for server variable and abort if being run from a web server
  if (isset($_SERVER['SERVER_NAME']))
  {
    print "ERROR: Cannot be run from a web page\n";
    print "Aborting.";
    exit;
  }

  print date('Y-m-d-H:i:s') . ": checking to run autoupdate\n";

  require "../../docroot/page.inc";
  require "../../docroot/bluealliance.inc";

  // set up db connection
  $connection = dbsetup();

  // check if autoupdate state is on to run
  if (! (tba_get_autoupdate())) exit;


  print date('Y-m-d-H:i:s'). "Competition System Batch Run\n";

      // inform user
      print date('Y-m-d-H:i:s') . " Processing event matches...\n";
      if (tba_get_matches())
        print date('Y-m-d-H:i:s') . " Blue Alliance operation successful.\n";
      else
        print date('Y-m-d-H:i:s') . " Blue Alliance operation failed.  Please check errors.\n";

      // rankings
      // inform user
      print date('Y-m-d-H:i:s') . " Retrieving rankings...\n";
      if (tba_get_event_rankings())
        print date('Y-m-d-H:i:s') . " Blue Alliance operation successful.\n";
      else
        print date('Y-m-d-H:i:s') . " Blue Alliance operation failed.  Please check errors.\n";



      // oprs
      // inform user
      print date('Y-m-d-H:i:s') . " Retrieving oprs...\n";
      if (tba_get_event_oprs())
        print date('Y-m-d-H:i:s') . " Blue Alliance operation successful.\n";
      else
        print date('Y-m-d-H:i:s') . " Blue Alliance operation failed.  Please check errors.\n";


      // predictions
      // inform user
      print date('Y-m-d-H:i:s') . " Retrieving event stats...\n";
      if (tba_get_event_predictions())
        print date('Y-m-d-H:i:s') . " Blue Alliance operation successful.\n";
      else
        print date('Y-m-d-H:i:s') . " Blue Alliance operation failed.  Please check errors.\n";

   // close database
   if ($connection)
     mysqli_close($connection);

 ?>