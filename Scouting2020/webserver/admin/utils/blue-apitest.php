<?php
//
// Blue Alliance API test
//

include ('../../docroot/lib/httpful.phar');

$year='2020';
$event='cada';
$ourteam='frc0100';

$tba_AuthKey = "DYUrT56p95B3fRnwCn21l0DPirqWz9auOs6zTsULgMrk0A8Yh5XtZs7U3Y6g4rMc";

print "Test Blue Alliance API\n";

print "Testing for PHP curl library...";
curl_init("Dummy");
print "done.\n\n";

print "AuthKey:" . $tba_AuthKey . "\n\n";

$url = "https://www.thebluealliance.com/api/v3/status";
exit;

//$url = "https://www.thebluealliance.com/api/v3/events/{$year}";
// http://www.thebluealliance.com/api/v2

$response = \Httpful\Request::get($url)
    ->addHeader('X-TBA-Auth-Key',$tba_AuthKey)
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