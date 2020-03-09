<?php
//
// Blue Alliance API test
//

include ('../../docroot/lib/httpful.phar');

$year='2020';
$event='cala';
$team_key='frc0100';

$tba_AuthKey = "DYUrT56p95B3fRnwCn21l0DPirqWz9auOs6zTsULgMrk0A8Yh5XtZs7U3Y6g4rMc";

// compiled vars
$event_key = $year . $event;


print "AuthKey:" . $tba_AuthKey . "\n\n";

$url = "https://www.thebluealliance.com/api/v3/event/$event_key/matches/timeseries";

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

print "\n\nend.\n";

?>