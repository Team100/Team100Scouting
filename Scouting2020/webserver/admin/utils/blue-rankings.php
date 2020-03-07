<?php
//
//
//
// Blue Alliance utility to inspect rankings and show tag
//

include ('../../htdocs/lib/httpful.phar');

$year='2016';
$event='cada';

$ourteam='frc0100';
$tbaAppId= $ourteam . ':compsystem:v02';

print "AppID:" . $tbaAppId . "\n\n";

$url = "https://www.thebluealliance.com/api/v3/event/{$year}{$event}/rankings";

$response = \Httpful\Request::get($url)
    ->addHeader('X-TBA-Auth-Key',$tbaAppId)
    ->send();

print "Response from Blue Alliance\n";
print $url . "\n";
//print_r ($response);

print "\nYear: {$year}\n";
print "Event: {$event}\n";
print "\n\nRanking Array:\n";
print_r($response->body[0]);

print "\n\nend.\n";

?>