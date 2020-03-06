#
# Competition System - Custom Parameters Table Additions
#
# Generated: 2020-03-06 08:59:49
#
# Should be run after freshdb creation.  Can also be run as new params are
#  generated.  The script may error is column alterations already exist,
#  but it should make the new ones.
#
alter table teambot add column if not exists (home0 varchar (3));
alter table teambot add column if not exists (match0 varchar (3));
alter table teambot add column if not exists (balls_sed varchar (3));
alter table match_team add column if not exists (balls_sec varchar (3));
alter table match_team add column if not exists (cycle_time varchar (3));
#
# End of custom generated columns
#
