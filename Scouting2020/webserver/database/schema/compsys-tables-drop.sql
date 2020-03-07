#
# $Revision: 3.0 $
# $Date: 2016/03/14 23:00:02 $
#
# Red Rock Robotics, Wildhats, Verkler
# Competition System Table Schema
#
# Notes:
#  - drops all tables for a new instance of the competition system
#
#  - create table script is maintained in compsystem-tables.sql
#
#  - if you add a table, make sure it is included here for a drop table
#
#  - running this script also "DROPS THE DATA IN THE TABLES".  Be careful
#
#

drop table if exists event;
drop table if exists team;
drop table if exists team_history;
drop table if exists team_history_award;
drop table if exists teambot;
drop table if exists alliance;
drop table if exists alliance_team;
drop table if exists alliance_unavailable;
drop table if exists match_instance;
drop table if exists match_instance_alliance;
drop table if exists match_team;
#drop table if exists match_alliance_team;
drop table if exists schedule;
#drop table if exists championteam;
drop table if exists process_lock;
drop table if exists message;
drop table if exists user_profile;
drop table if exists topic;
drop table if exists documentation;
drop table if exists pagetodoc;
drop table if exists system_value;
drop table if exists tba_last_modified;
# 
# custom parameters
drop table if exists custom_param;
#
# end of drops
#