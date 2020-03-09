#
# Competition System - Custom Parameters Table Additions
#
# Generated: 2020-03-08 21:44:09
#
# Should be run after freshdb creation.  Can also be run as new params are
#  generated.  The script may error is column alterations already exist,
#  but it should make the new ones.
#


#
# Database mods for vargroup Bot


#
# Database mods for vargroup Match


#
# Database mods for vargroup tBA
alter table match_instance_alliance add column if not exists (f_initLineRobot1 varchar (20));
alter table match_instance_alliance add column if not exists (f_endgameRobot1 varchar (20));
alter table match_instance_alliance add column if not exists (f_initLineRobot2 varchar (20));
alter table match_instance_alliance add column if not exists (f_endgameRobot2 varchar (20));
alter table match_instance_alliance add column if not exists (f_initLineRobot3 varchar (20));
alter table match_instance_alliance add column if not exists (f_endgameRobot3 varchar (20));
alter table match_instance_alliance add column if not exists (f_autoCellsBottom varchar (20));
alter table match_instance_alliance add column if not exists (f_autoCellsOuter varchar (20));
alter table match_instance_alliance add column if not exists (f_autoCellsInner varchar (20));
alter table match_instance_alliance add column if not exists (f_teleopCellsBottom varchar (20));
alter table match_instance_alliance add column if not exists (f_teleopCellsOuter varchar (20));
alter table match_instance_alliance add column if not exists (f_teleopCellsInner varchar (20));
alter table match_instance_alliance add column if not exists (f_stage1Activated varchar (20));
alter table match_instance_alliance add column if not exists (f_stage2Activated varchar (20));
alter table match_instance_alliance add column if not exists (f_stage3Activated varchar (20));
alter table match_instance_alliance add column if not exists (f_stage3TargetColor varchar (20));
alter table match_instance_alliance add column if not exists (f_endgameRungIsLevel varchar (20));
alter table match_instance_alliance add column if not exists (f_autoInitLinePoints varchar (20));
alter table match_instance_alliance add column if not exists (f_autoCellPoints varchar (20));
alter table match_instance_alliance add column if not exists (f_autoPoints varchar (20));
alter table match_instance_alliance add column if not exists (f_teleopCellPoints varchar (20));
alter table match_instance_alliance add column if not exists (f_controlPanelPoints varchar (20));
alter table match_instance_alliance add column if not exists (f_endgamePoints varchar (20));
alter table match_instance_alliance add column if not exists (f_teleopPoints varchar (20));
alter table match_instance_alliance add column if not exists (f_shieldOperationalR varchar (20));
alter table match_instance_alliance add column if not exists (f_shieldEnergizedRan varchar (20));
alter table match_instance_alliance add column if not exists (f_tba_shieldEnergize varchar (20));
alter table match_instance_alliance add column if not exists (f_tba_numRobotsHangi varchar (20));
alter table match_instance_alliance add column if not exists (f_foulCount varchar (20));
alter table match_instance_alliance add column if not exists (f_techFoulCount varchar (20));
alter table match_instance_alliance add column if not exists (f_adjustPoints varchar (20));
alter table match_instance_alliance add column if not exists (f_foulPoints varchar (20));
alter table match_instance_alliance add column if not exists (f_rp varchar (20));
alter table match_instance_alliance add column if not exists (f_totalPoints varchar (20));
#
# End of custom generated columns
#
