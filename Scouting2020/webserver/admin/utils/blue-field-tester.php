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
$event_key = $year . $event;


print "AuthKey:" . $tba_AuthKey . "\n\n";

$url = "https://www.thebluealliance.com/api/v3/event/$event_key/rankings";

// URLs:
//
//  https://www.thebluealliance.com/api/v3/status
//  https://www.thebluealliance.com/api/v3/event/$event_key/teams
//  https://www.thebluealliance.com/api/v3/event/$event_key/matches
//  https://www.thebluealliance.com/api/v3/event/$event_key/oprs
//  https://www.thebluealliance.com/api/v3/event/$event_key/insights
//  https://www.thebluealliance.com/api/v3/event/$event_key/oprs
//  https://www.thebluealliance.com/api/v3/event/$event_key/rankings
//  https://www.thebluealliance.com/api/v3/event/$event_key/predictions
//  https://www.thebluealliance.com/api/v3/event/$event_key/matches/timeseries
//  https://www.thebluealliance.com/api/v3/event/$event_key/
//  https://www.thebluealliance.com/api/v3/event/$event_key/
//  https://www.thebluealliance.com/api/v3/event/$event_key/
//
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


$response = \Httpful\Request::get($url)
    ->addHeader('X-TBA-Auth-Key',$tba_AuthKey)
    ->send();

print "Response from Blue Alliance\n";
print $url . "\n\n";

print_r ($response);

//print "\nYear: {$year}\n";
//print "Event: {$event}\n";
//print "\n\nRanking Array:\n";
//print_r($response->body[0]);


// uncomment to exit early
// exit ():



//
// show more
//

// example loop:
//  foreach ($response->body as $key=>$matchobj)
//  foreach ($response->body->rankings as $key=>$rankobj)
//


print "\n\n\nArray Body responses:\n";





// uncomment to exit early
exit ():


// example

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