<?php
//
// Blue Alliance API field tester
//

$year='2020';
$event='cala';
$team_key='frc0100';

$tba_AuthKey = "DYUrT56p95B3fRnwCn21l0DPirqWz9auOs6zTsULgMrk0A8Yh5XtZs7U3Y6g4rMc";

require ('../../docroot/page.inc');
require ('../../docroot/bluealliance.inc');
//include ('../../docroot/lib/httpful.phar');

// compiled vars
$sys_event_id = $year . $event;


print "AuthKey:" . $tba_AuthKey . "\n\n";

$url = "https://www.thebluealliance.com/api/v3/team/frc254/robots";

// URLs:
//
//  https://www.thebluealliance.com/api/v3/status
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/teams
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/matches
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/oprs
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/insights
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/oprs
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/rankings
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/predictions
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/matches/timeseries
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/
//  https://www.thebluealliance.com/api/v3/event/$sys_event_id/
//  https://www.thebluealliance.com/api/v3/team/frc254/robots
//
//  https://www.thebluealliance.com/api/v3/team/$team_key/robots
//
//
//
//
//
//
//
//
//
//

//
// original method (uncomment to use)
//$tba_response = \Httpful\Request::get($url)
//    ->addHeader('X-TBA-Auth-Key',$tba_AuthKey)
//    ->send();

// blue-alliance.inc methos
if (! ($tba_response = tba_getdata($url, FALSE)))
{
  print "API returned " . $tba_error['message'] . "<br>\n";
}
else

print "Response from Blue Alliance\n";
print $url . "\n\n";

print_r ($tba_response);

// uncomment to exit early
// exit;

//
// show more
//

// example loop:
//  foreach ($tba_response->body as $key=>$matchobj)
//  foreach ($tba_response->body->rankings as $key=>$rankobj)
//  foreach ($tba_response->body as $element)
//

// uncomment to exit early
//exit;

foreach ($tba_response->body as $element)
{
  print "FRC team: " . $element->team_key . $element->year;
}


exit;
//
// custom param
$tag="power_cells_scored";
print "\n\n\nArray Body responses:\n";

    // loop through each tBA_Bot field and pull up mean, then add for each team
    foreach($dispfields["tBA_Bot"] as $element)
    {
      foreach($tba_response->body->stat_mean_vars->qual->$tag->mean as $team_key=>$value)
      {
        print "Team Key " . $team_key ."<br>\n";
      }
}

exit;



$stats = array("oprs","dprs","ccwms");
foreach ($stats as $stat)
{
  foreach ($response->body->$stat as $teamkey=>$value)
  {
    print $teamkey . ":" . $value . "\n";
  }
}

// example
exit;

foreach ($response->body->rankings as $key=>$rankobj)
{
  print "Team_key: {$rankobj->team_key}<<\n";
  print "Rank wins:{$rankobj->record->wins}<<\n";

  // find team
  //$tba_dbarray = tba_map_teamnum($rankobj, array());
  $tba_dbarray = array("teamnum"=>$rankobj->team_key);

  // convert to teamnum
  $tba_dbarray = tba_convert_teamnum($tba_dbarray);

  // mapfields
  $tba_dbarray = tba_mapfields($tba_record_to_teambot, $rankobj->record, $tba_dbarray);

  // show array
  print "\nLoaded DB array:\n";
  print_r($tba_dbarray);

}



print "\n\nend.\n";

?>