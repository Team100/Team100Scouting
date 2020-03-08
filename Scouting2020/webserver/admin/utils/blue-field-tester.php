<?php
//
// Blue Alliance API test
//

include ('../../docroot/lib/httpful.phar');

$year='2020';
$event='cala';
$ourteam='frc0100';

$tba_AuthKey = "DYUrT56p95B3fRnwCn21l0DPirqWz9auOs6zTsULgMrk0A8Yh5XtZs7U3Y6g4rMc";

// compiled vars
$event_key = $year . $event;


print "AuthKey:" . $tba_AuthKey . "\n\n";

$url = "https://www.thebluealliance.com/api/v3/event/$event_key/matches";

// URLs:
//
//  https://www.thebluealliance.com/api/v3/status
//  https://www.thebluealliance.com/api/v3/event/$event_key/teams
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