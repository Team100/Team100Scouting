#
# Competition System - Custom Parameters Table Additions
#
# Generated: 2020-03-10 23:02:22
#
# Should be run after freshdb creation.  Can also be run as new params are
#  generated.  The script should not try to recreate columns if they already
#  exist, but will also not drop columns that are not used any more.  (future feature?)
#


#
# Database mods for vargroup Bot
alter table teambot add column if not exists (Weight int );
alter table teambot add column if not exists (EndGameCapable boolean );


#
# Database mods for vargroup Match
alter table match_team add column if not exists (OutsideBalls int );
alter table match_team add column if not exists (LowBalls int );


#
# Database mods for vargroup tBA_Bot
alter table teambot add column if not exists (f_power_cells_scored int );
alter table teambot add column if not exists (f_endgame_points int );


#
# Database mods for vargroup tBA_Match
alter table match_instance_alliance add column if not exists (f_initLineRobot1 varchar (20));
alter table match_instance_alliance add column if not exists (f_endgameRobot1 varchar (20));
alter table match_instance_alliance add column if not exists (f_initLineRobot2 varchar (20));
alter table match_instance_alliance add column if not exists (f_endgameRobot2 varchar (20));
alter table match_instance_alliance add column if not exists (f_initLineRobot3 varchar (20));
alter table match_instance_alliance add column if not exists (f_endgameRobot3 varchar (20));
alter table match_instance_alliance add column if not exists (f_autoCellsBottom int );
alter table match_instance_alliance add column if not exists (f_autoCellsOuter int );
alter table match_instance_alliance add column if not exists (f_autoCellsInner int );
alter table match_instance_alliance add column if not exists (f_teleopCellsBottom int );
alter table match_instance_alliance add column if not exists (f_teleopCellsOuter int );
alter table match_instance_alliance add column if not exists (f_teleopCellsInner int );
alter table match_instance_alliance add column if not exists (f_stage1Activated boolean );
alter table match_instance_alliance add column if not exists (f_stage2Activated boolean );
alter table match_instance_alliance add column if not exists (f_stage3Activated boolean );
alter table match_instance_alliance add column if not exists (f_stage3TargetColor varchar (20));
alter table match_instance_alliance add column if not exists (f_endgameRungIsLevel varchar (20));
alter table match_instance_alliance add column if not exists (f_autoInitLinePoints int );
alter table match_instance_alliance add column if not exists (f_autoCellPoints int );
alter table match_instance_alliance add column if not exists (f_autoPoints int );
alter table match_instance_alliance add column if not exists (f_teleopCellPoints int );
alter table match_instance_alliance add column if not exists (f_controlPanelPoints int );
alter table match_instance_alliance add column if not exists (f_endgamePoints int );
alter table match_instance_alliance add column if not exists (f_teleopPoints int );
alter table match_instance_alliance add column if not exists (f_shieldOperationalR boolean );
alter table match_instance_alliance add column if not exists (f_shieldEnergizedRan boolean );
alter table match_instance_alliance add column if not exists (f_tba_shieldEnergize boolean );
alter table match_instance_alliance add column if not exists (f_tba_numRobotsHangi int );
alter table match_instance_alliance add column if not exists (f_foulCount int );
alter table match_instance_alliance add column if not exists (f_techFoulCount int );
alter table match_instance_alliance add column if not exists (f_adjustPoints int );
alter table match_instance_alliance add column if not exists (f_foulPoints int );
alter table match_instance_alliance add column if not exists (f_rp int );
alter table match_instance_alliance add column if not exists (f_totalPoints int );
#
# End of custom generated columns
#
