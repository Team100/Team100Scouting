#
# Competition System - Custom Parameters Table Additions
#
# Generated: 2020-03-06 11:23:22
#
# Should be run after freshdb creation.  Can also be run as new params are
#  generated.  The script may error is column alterations already exist,
#  but it should make the new ones.
#
alter table teambot add column if not exists (opstatus varchar (4));
alter table teambot add column if not exists (drivetrain varchar (20));
alter table teambot add column if not exists (shooter varchar (1));
alter table teambot add column if not exists (highshooter varchar (1));
alter table teambot add column if not exists (pickup varchar (1));
alter table teambot add column if not exists (scaling varchar (1));
alter table teambot add column if not exists (specialmech varchar (1));
alter table teambot add column if not exists (portcullis varchar (2));
alter table teambot add column if not exists (cheval varchar (2));
alter table teambot add column if not exists (moat varchar (2));
alter table teambot add column if not exists (ramparts varchar (2));
alter table teambot add column if not exists (drawbridge varchar (2));
alter table teambot add column if not exists (sallyPort varchar (2));
alter table teambot add column if not exists (rockwall varchar (2));
alter table teambot add column if not exists (roughterrain varchar (2));
alter table teambot add column if not exists (lowbar varchar (2));
alter table teambot add column if not exists (speedrt varchar (1));
alter table teambot add column if not exists (pickuprt varchar (1));
alter table teambot add column if not exists (lowrt varchar (1));
alter table teambot add column if not exists (highrt varchar (1));
alter table teambot add column if not exists (breachrt varchar (1));
alter table match_team add column if not exists (autoscorebot varchar (3));
alter table match_team add column if not exists (autoscoretop varchar (3));
alter table match_team add column if not exists (AutoRockWall varchar (3));
alter table match_team add column if not exists (AutoMoat varchar (3));
alter table match_team add column if not exists (AutoDrawbridge varchar (3));
alter table match_team add column if not exists (AutoCheval varchar (3));
alter table match_team add column if not exists (AutoLowBar varchar (3));
alter table match_team add column if not exists (startposition varchar (3));
alter table match_team add column if not exists (MissesHigh varchar (3));
alter table match_team add column if not exists (MissesLow varchar (3));
alter table match_team add column if not exists (PercentAccuracy varchar (3));
alter table match_team add column if not exists (driverrating varchar (3));
alter table match_team add column if not exists (pickuprating varchar (3));
alter table match_team add column if not exists (BreacherRating varchar (3));
alter table match_team add column if not exists (RockWallMatch varchar (3));
alter table match_team add column if not exists (RockWallDiff varchar (3));
alter table match_team add column if not exists (MoatMatch varchar (3));
alter table match_team add column if not exists (MoatDiff varchar (3));
alter table match_team add column if not exists (DrawbridgeMatch varchar (3));
alter table match_team add column if not exists (DrawbridgeDiff varchar (3));
alter table match_team add column if not exists (ChevalMatch varchar (3));
alter table match_team add column if not exists (ChevalDiff varchar (3));
alter table match_team add column if not exists (LowBarMatch varchar (3));
alter table match_team add column if not exists (LowBarDiff varchar (3));
alter table match_team add column if not exists (robottype varchar (3));
alter table match_team add column if not exists (PickupSpeed varchar (3));
alter table match_team add column if not exists (PickupStable varchar (3));
alter table match_team add column if not exists (telescorebot varchar (3));
alter table match_team add column if not exists (telescoretop varchar (3));
alter table match_team add column if not exists (TechFoul varchar (3));
alter table match_team add column if not exists (FinalPosit varchar (3));
alter table match_team add column if not exists (redcard varchar (3));
alter table match_team add column if not exists (Disabled varchar (3));
alter table match_team add column if not exists (Broken varchar (3));
#
# End of custom generated columns
#
