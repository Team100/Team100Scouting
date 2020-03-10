<?php
//
// Competition System - Custom Parameters
//
// Generated: 2020-03-10 14:50:13
//


//
// Parameters for vargroup Bot
//
$dispfields["Bot"] = array();
$dispfields["Bot"][0] = array("used"=>TRUE,"tag"=>"Weight", "display"=>"Robot Weight", "inputlen"=>4,"maxlen"=>4, "default_value"=>"155");
$dispfields["Bot"][1] = array("used"=>TRUE,"tag"=>"EndGame", "display"=>"End Game Capable", "inputlen"=>3,"maxlen"=>3, "default_value"=>"1");


//
// Parameters for vargroup Match
//
$dispfields["Match"] = array();
$dispfields["Match"][0] = array("used"=>TRUE,"tag"=>"OutsideBalls", "display"=>"Outside Balls", "inputlen"=>3,"maxlen"=>3, "default_value"=>"");
$dispfields["Match"][1] = array("used"=>TRUE,"tag"=>"LowBalls", "display"=>"Low Balls", "inputlen"=>3,"maxlen"=>3, "default_value"=>"");


//
// Parameters for vargroup tBA_Bot
//
$tbaFields["Bot"]  = array();
$dispfields["tBA_Bot"][0] = array("used"=>TRUE,"tag"=>"f_power_cells", "tBAtag"=>"power_cells_scored", "display"=>"F Power Cells", "maxlen"=>5 );
$dispfields["tBA_Bot"][1] = array("used"=>TRUE,"tag"=>"f_endgame_pts", "tBAtag"=>"endgame_points", "display"=>"F Endgame Points", "maxlen"=>5 );


//
// Parameters for vargroup tBA_Match
//
$tbaFields["Match"]  = array();
$dispfields["tBA_Match"][0] = array("used"=>TRUE,"tag"=>"f_initLineRobot1", "tBAtag"=>"initLineRobot1", "display"=>"f_initLineRobot1", "maxlen"=>20 );
$dispfields["tBA_Match"][1] = array("used"=>TRUE,"tag"=>"f_endgameRobot1", "tBAtag"=>"endgameRobot1", "display"=>"f_endgameRobot1", "maxlen"=>20 );
$dispfields["tBA_Match"][2] = array("used"=>TRUE,"tag"=>"f_initLineRobot2", "tBAtag"=>"initLineRobot2", "display"=>"f_initLineRobot2", "maxlen"=>20 );
$dispfields["tBA_Match"][3] = array("used"=>TRUE,"tag"=>"f_endgameRobot2", "tBAtag"=>"endgameRobot2", "display"=>"f_endgameRobot2", "maxlen"=>20 );
$dispfields["tBA_Match"][4] = array("used"=>TRUE,"tag"=>"f_initLineRobot3", "tBAtag"=>"initLineRobot3", "display"=>"f_initLineRobot3", "maxlen"=>20 );
$dispfields["tBA_Match"][5] = array("used"=>TRUE,"tag"=>"f_endgameRobot3", "tBAtag"=>"endgameRobot3", "display"=>"f_endgameRobot3", "maxlen"=>20 );
$dispfields["tBA_Match"][6] = array("used"=>TRUE,"tag"=>"f_autoCellsBottom", "tBAtag"=>"autoCellsBottom", "display"=>"f_autoCellsBottom", "maxlen"=>20 );
$dispfields["tBA_Match"][7] = array("used"=>TRUE,"tag"=>"f_autoCellsOuter", "tBAtag"=>"autoCellsOuter", "display"=>"f_autoCellsOuter", "maxlen"=>20 );
$dispfields["tBA_Match"][8] = array("used"=>TRUE,"tag"=>"f_autoCellsInner", "tBAtag"=>"autoCellsInner", "display"=>"f_autoCellsInner", "maxlen"=>20 );
$dispfields["tBA_Match"][9] = array("used"=>TRUE,"tag"=>"f_teleopCellsBottom", "tBAtag"=>"teleopCellsBottom", "display"=>"f_teleopCellsBottom", "maxlen"=>20 );
$dispfields["tBA_Match"][10] = array("used"=>TRUE,"tag"=>"f_teleopCellsOuter", "tBAtag"=>"teleopCellsOuter", "display"=>"f_teleopCellsOuter", "maxlen"=>20 );
$dispfields["tBA_Match"][11] = array("used"=>TRUE,"tag"=>"f_teleopCellsInner", "tBAtag"=>"teleopCellsInner", "display"=>"f_teleopCellsInner", "maxlen"=>20 );
$dispfields["tBA_Match"][12] = array("used"=>TRUE,"tag"=>"f_stage1Activated", "tBAtag"=>"stage1Activated", "display"=>"f_stage1Activated", "maxlen"=>20 );
$dispfields["tBA_Match"][13] = array("used"=>TRUE,"tag"=>"f_stage2Activated", "tBAtag"=>"stage2Activated", "display"=>"f_stage2Activated", "maxlen"=>20 );
$dispfields["tBA_Match"][14] = array("used"=>TRUE,"tag"=>"f_stage3Activated", "tBAtag"=>"stage3Activated", "display"=>"f_stage3Activated", "maxlen"=>20 );
$dispfields["tBA_Match"][15] = array("used"=>TRUE,"tag"=>"f_stage3TargetColor", "tBAtag"=>"stage3TargetColor", "display"=>"f_stage3TargetColor", "maxlen"=>20 );
$dispfields["tBA_Match"][16] = array("used"=>TRUE,"tag"=>"f_endgameRungIsLevel", "tBAtag"=>"endgameRungIsLevel", "display"=>"f_endgameRungIsLevel", "maxlen"=>20 );
$dispfields["tBA_Match"][17] = array("used"=>TRUE,"tag"=>"f_autoInitLinePoints", "tBAtag"=>"autoInitLinePoints", "display"=>"f_autoInitLinePoints", "maxlen"=>20 );
$dispfields["tBA_Match"][18] = array("used"=>TRUE,"tag"=>"f_autoCellPoints", "tBAtag"=>"autoCellPoints", "display"=>"f_autoCellPoints", "maxlen"=>20 );
$dispfields["tBA_Match"][19] = array("used"=>TRUE,"tag"=>"f_autoPoints", "tBAtag"=>"autoPoints", "display"=>"f_autoPoints", "maxlen"=>20 );
$dispfields["tBA_Match"][20] = array("used"=>TRUE,"tag"=>"f_teleopCellPoints", "tBAtag"=>"teleopCellPoints", "display"=>"f_teleopCellPoints", "maxlen"=>20 );
$dispfields["tBA_Match"][21] = array("used"=>TRUE,"tag"=>"f_controlPanelPoints", "tBAtag"=>"controlPanelPoints", "display"=>"f_controlPanelPoints", "maxlen"=>20 );
$dispfields["tBA_Match"][22] = array("used"=>TRUE,"tag"=>"f_endgamePoints", "tBAtag"=>"endgamePoints", "display"=>"f_endgamePoints", "maxlen"=>20 );
$dispfields["tBA_Match"][23] = array("used"=>TRUE,"tag"=>"f_teleopPoints", "tBAtag"=>"teleopPoints", "display"=>"f_teleopPoints", "maxlen"=>20 );
$dispfields["tBA_Match"][24] = array("used"=>TRUE,"tag"=>"f_shieldOperationalR", "tBAtag"=>"shieldOperationalRankingPoint", "display"=>"f_shieldOperationalR", "maxlen"=>20 );
$dispfields["tBA_Match"][25] = array("used"=>TRUE,"tag"=>"f_shieldEnergizedRan", "tBAtag"=>"shieldEnergizedRankingPoint", "display"=>"f_shieldEnergizedRan", "maxlen"=>20 );
$dispfields["tBA_Match"][26] = array("used"=>TRUE,"tag"=>"f_tba_shieldEnergize", "tBAtag"=>"tba_shieldEnergizedRankingPointFromFoul", "display"=>"f_tba_shieldEnergize", "maxlen"=>20 );
$dispfields["tBA_Match"][27] = array("used"=>TRUE,"tag"=>"f_tba_numRobotsHangi", "tBAtag"=>"tba_numRobotsHanging", "display"=>"f_tba_numRobotsHangi", "maxlen"=>20 );
$dispfields["tBA_Match"][28] = array("used"=>TRUE,"tag"=>"f_foulCount", "tBAtag"=>"foulCount", "display"=>"f_foulCount", "maxlen"=>20 );
$dispfields["tBA_Match"][29] = array("used"=>TRUE,"tag"=>"f_techFoulCount", "tBAtag"=>"techFoulCount", "display"=>"f_techFoulCount", "maxlen"=>20 );
$dispfields["tBA_Match"][30] = array("used"=>TRUE,"tag"=>"f_adjustPoints", "tBAtag"=>"adjustPoints", "display"=>"f_adjustPoints", "maxlen"=>20 );
$dispfields["tBA_Match"][31] = array("used"=>TRUE,"tag"=>"f_foulPoints", "tBAtag"=>"foulPoints", "display"=>"f_foulPoints", "maxlen"=>20 );
$dispfields["tBA_Match"][32] = array("used"=>TRUE,"tag"=>"f_rp", "tBAtag"=>"rp", "display"=>"f_rp", "maxlen"=>20 );
$dispfields["tBA_Match"][33] = array("used"=>TRUE,"tag"=>"f_totalPoints", "tBAtag"=>"totalPoints", "display"=>"f_totalPoints", "maxlen"=>20 );


//
//
// tBA - Blue Alliance - custom fields score map
//   tBA score fields to custom score fields
$tba_score_to_match_alliance = array (
  "power_cells_scored"=>"f_power_cells",
  "endgame_points"=>"f_endgame_pts",
  "initLineRobot1"=>"f_initLineRobot1",
  "endgameRobot1"=>"f_endgameRobot1",
  "initLineRobot2"=>"f_initLineRobot2",
  "endgameRobot2"=>"f_endgameRobot2",
  "initLineRobot3"=>"f_initLineRobot3",
  "endgameRobot3"=>"f_endgameRobot3",
  "autoCellsBottom"=>"f_autoCellsBottom",
  "autoCellsOuter"=>"f_autoCellsOuter",
  "autoCellsInner"=>"f_autoCellsInner",
  "teleopCellsBottom"=>"f_teleopCellsBottom",
  "teleopCellsOuter"=>"f_teleopCellsOuter",
  "teleopCellsInner"=>"f_teleopCellsInner",
  "stage1Activated"=>"f_stage1Activated",
  "stage2Activated"=>"f_stage2Activated",
  "stage3Activated"=>"f_stage3Activated",
  "stage3TargetColor"=>"f_stage3TargetColor",
  "endgameRungIsLevel"=>"f_endgameRungIsLevel",
  "autoInitLinePoints"=>"f_autoInitLinePoints",
  "autoCellPoints"=>"f_autoCellPoints",
  "autoPoints"=>"f_autoPoints",
  "teleopCellPoints"=>"f_teleopCellPoints",
  "controlPanelPoints"=>"f_controlPanelPoints",
  "endgamePoints"=>"f_endgamePoints",
  "teleopPoints"=>"f_teleopPoints",
  "shieldOperationalRankingPoint"=>"f_shieldOperationalR",
  "shieldEnergizedRankingPoint"=>"f_shieldEnergizedRan",
  "tba_shieldEnergizedRankingPointFromFoul"=>"f_tba_shieldEnergize",
  "tba_numRobotsHanging"=>"f_tba_numRobotsHangi",
  "foulCount"=>"f_foulCount",
  "techFoulCount"=>"f_techFoulCount",
  "adjustPoints"=>"f_adjustPoints",
  "foulPoints"=>"f_foulPoints",
  "rp"=>"f_rp",
  "totalPoints"=>"f_totalPoints" );

//
// End of custom generated file
//
?>