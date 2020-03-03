<?php
//
// Blue Alliance API test
//

include ('../../docroot/lib/httpful.phar');

$year='2020';
$event='cada';
$ourteam='frc0100';

$tbaAPIkey = "DYUrT56p95B3fRnwCn21l0DPirqWz9auOs6zTsULgMrk0A8Yh5XtZs7U3Y6g4rMc";

print "AuthKey:" . $tbaAPIkey . "\n\n";

$url = "https://www.thebluealliance.com/api/v3/status";

//$url = "https://www.thebluealliance.com/api/v3/events/{$year}";


$response = \Httpful\Request::get($url)
    ->addHeader('X-TBA-Auth-Key',$tbaAPIkey)
    ->send();

print "Response from Blue Alliance\n";
print $url . "\n";

print_r ($response);

print "\nYear: {$year}\n";
print "Event: {$event}\n";
//print "\n\nRanking Array:\n";
//print_r($response->body[0]);

print "\n\nend.\n";

?>