<?php
//
// $Revision: 3.0 $
// $Date: 2016/03/14 22:56:41 $
//
// teammatchfunctions.inc
//
// Functions to be used by team and match libraries
//

// no require needed, as expect we have already loaded needed requires


// 
// function colorteammatch ( $teamnum, $host_team, $detailrow, $upcoming )
//  $teamnum - teamnum being displayed
//  $detailrow - detailrow
//  $upcoming - array of matches with or against compiled by database
//
// returns host_team_row TRUE/FALSE
//
// directly prints style background color depending on relationship with home team to teamnum


function colorteammatch ( $teamnum, $detailrow, $upcoming )
{
  global $host_teamnum;
  global $lyellow;
  global $lblue;
  global $lgreen;
  global $lred;

  $host_team_row = FALSE;
  
  // if the team is the host team, mark with color. Also set flag
  if ( $teamnum == $host_teamnum)
  {
    print " style=\"background-color: {$lyellow}\" ";
    $host_team_row = TRUE;
  }	
  // otherwise check whether we're playing with or against them in the array, and the right type
  else if (array_key_exists($teamnum, $upcoming) && ($detailrow['type'] == $upcoming[$teamnum]['type']))
  {
    // set with/against
    if (isset($upcoming[$teamnum]['with_matchnum'])) $with=$upcoming[$teamnum]['with_matchnum']; else $with=0;
    if (isset($upcoming[$teamnum]['against_matchnum'])) $against=$upcoming[$teamnum]['against_matchnum']; else $against=0;
    $matchnum = $detailrow['matchnum'];
    
    // if playing agaist and with, then blue
    if ($matchnum < $with && $matchnum < $against)
	print " style=\"background-color: {$lblue}\" ";
    // else if playing with
    else if ($matchnum  < $with)
	print " style=\"background-color: {$lgreen}\" ";
    // else if play against
    else if ($matchnum  < $against)
	print " style=\"background-color: {$lred}\" ";
   }

   return($host_team_row);
  
} // end of function

//
// ****************************************************************************
//
// end of teammatchfuntions.inc -- do not leave any lines under the end of this closure