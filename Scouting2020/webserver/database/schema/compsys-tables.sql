#
# $Revision: 3.0 $
# $Date: 2016/03/14 23:00:02 $
#
# Red Rock Robotics, WildHats, Verkler
# Competition System Table Schema
#
# Notes:
#  - creates all tables for a new instance of the competition system
#
#  - if you modify a table, make _sure_ the modification is also made in this schema
#
#  - to execute, run mysql as:
#      mysql -D db-name -u user -p < scriptname
#
#  - document the purpose of a table by in the heading before the table
#
#  - a "drop table" script is maintained in compsystem-tables-drop.sql
#

#
# General schema notes
#  - Schema includes patterns from Blue Alliance API but does not adhere to its
#    structure or key system entirely
#  - Assumes that the system is refreshed yearly, but that team info persists.
#    This means:
#      - Fields that Blue Alliance can modify are tagged as such in the schema, 
#         with tBA (the Blue Alliance)
#      - The schema must work for multiple regional competitions and for nationals,
#         which looks like a collection of regional competitions.
#
#  Future development:
# 
#  - if we ever decide to support multi-instance, each table/index will need modification
#    to as a host_team.  Or event_id could be co-opted.  If adding a multi-instance id
#    just follow every event_id in the system.
#

#
# Event
# 
# Event ID, used to qualify data entry and use for a specific regional competition
#
# For nationals, we may have scouting for each of four fields.  Building in ability to clone
#  this app and use it in multiple places, then export and import those data into a master, or 
#  manage instances of the app and team, using the same event model.
#
#
create table event
 (
  event_id varchar(10),		# tBA key (event key), format yyyy[Event_Code]
  name varchar(20),             # tBA short_name
  long_name varchar(100),       # tBA name, official name
  event_code varchar(4),        # tBA event_code
  event_type varchar(20),       # tBA event_type_string, human-readable event, i.e. 'Regional'
  event_type_id int,            # tBA event_type, with a number code
  year int,                     # tBA year
  city varchar(35),		# tBA city of team
  state_prov varchar(20),	# tBA state_prov of team
  country varchar(25),		# tBA country of team
  location varchar(80),		# tBA location, location of team compiled from c,s,c
  website varchar(100),         # tBA website, event webssite	
  primary key (event_id)
 );



#
# Team
#
# General team information, intended to be carried forward year after year
#
# 
create table team
 (
  teamnum  int, 		# FIRST team number - primary key.  We do not use frcNNNN, just the NNNN
                                #   Note: the mapping from tBA is done by mapping function
  locked varchar(12), 		# row locked for editing by user.  Can clear in application.
  name varchar(50),		# tBA, FIRST nickname
  nickname varchar(30),		# our nickname for team
  rating int,                   # our 0-9 rating of team capabilities and competencies
  org varchar(80),		# high school or other organization
  city varchar(35),		# tBA city of team
  state_prov varchar(20),	# tBA state_prov of team
  country varchar(25),		# tBA country of team
  location varchar(80),		# tBA location, location of team compiled from c,s,c
  students int,			# number of students on team
  website varchar(80),		# tBA website, team web site
  sponsors varchar(1000),	# tBA name, team sponsors
  rookie_year int, 		# tBA rookie_year
  notes text(5000),		# notes on our interaction with the team
  primary key (teamnum)
 );


#
# team history
#
# team history of events, as loaded from Blue Alliance, team history object
#
# Foreign key from team table
#
create table team_history
 (
  teamnum  int, 		# FIRST team number - foreign key from team table
  event_id varchar(10),         # tBA event_key ("key") in the history object.  Note: not a foreign key to event table
  year int,                     # tBA year
  reg_name varchar(60),         # tBA name of regional
  primary key (teamnum,event_id)
 );
 


#
# team history award
#
# team history of awards at each event, as loaded from Blue Alliance, team history award object
#
# Foreign key from team table
#
create table team_history_award
 (
  teamnum  int, 		# FIRST team number - foreign key from team table
  event_id varchar(10),         # tBA event_key ("key") in the team history award object Note: not a foreign key to event table
  award_type varchar(3),        # tBA award_type (integer)
  award_name varchar(100),       # tBA name in team history object
  primary key (teamnum,event_id,award_type)
 );



#
# teambot
#
# Information on the team and bot at the event.  
#  There should only be one entry in this table for event players
# 
# Foreign key from team table
#
create table teambot
 (
  event_id varchar(8),          # FK to event table 
  teamnum  int, 		# FIRST team number - foreign key from team table
  locked varchar(12), 		# row locked for editing by user.  Can clear in application.
  updatedby varchar(200), 	# last updated by users
  bot_name varchar(30),         # tBA robot name
  f_ranking int,                # tBA ranking from FIRST
  f_rank_score real,            # tBA seed points from FIRST
  f_record varchar(8),          # tBA FIRST record, W-L-T
  f_games_played int,            # tBA FIRST games played
  f_rankparam0 real,            # tBA FIRST parameter 0 in game-specific rankings
  f_rankparam1 real,            # tBA FIRST parameter 1 in game-specific rankings
  f_rankparam2 real,            # tBA FIRST parameter 2 in game-specific rankings
  f_rankparam3 real,            # tBA FIRST parameter 3 in game-specific rankings
  f_rankparam4 real,            # tBA FIRST parameter 4 in game-specific rankings
  f_rankparam5 real,            # tBA FIRST parameter 5 in game-specific rankings
  f_rankparam6 real,            # tBA FIRST parameter 6 in game-specific rankings
  f_rankparam7 real,            # tBA FIRST parameter 7 in game-specific rankings
  f_rankparam8 real,            # tBA FIRST parameter 8 in game-specific rankings
  opr real,                     # tBA oprs, Offensive power rating
  dpr real,                     # tBA dprs, defensive power rating
  ccwm real,                    # tBA ccwm, calculated contribution to winning margin
  rank_overall real,		# overall rank (by us)
  rating_overall int,		# 0-9 rating as an overall bot
  rating_overall_off int,	# 0-9 rating offensively
  rating_overall_def int,	# 0-9 rating defensively
  rank_pos1 real,		# overall rank in position 1
  rating_pos1 int,		# 0-9 rating in position 1
  rank_pos2 real,		# overall rank in position 2
  rating_pos2 int,		# 0-9 rating in position 2
  rank_pos3 real,		# overall rank in position 3
  rating_pos3 int,		# 0-9 rating in position 3
  rating_driver int,            # 0-9 rating for driver
  offense_analysis text(1000),	# offense analysis (text)
  defense_analysis text(1000), 	# defense analysis (text)
  pos1_analysis text(1000),		# position 1 analysis (text)
  pos2_analysis text(1000),		# position 2 analysis (text)
  pos3_analysis text(1000),		# position 3 analysis (text)
  robot_analysis text(1000),		# overall robot analysis /* candidate to DEPRICATE */
  driver_analysis text(1000),		# driver analysis
  with_recommendation text(1000),	# recommendation if partnered with
  against_recommendation text(1000),	# recommendation if partnered against
  notes text(1000),                  	# general notes
  pit_notes text(1000),              	# notes from pit
  primary key(event_id,teamnum)
 );


#
# Alliance
#
# Only used in scouting for finals.  As alliances will play as a group, we start
#  scouting and ,atch evaluating only competitively (playing against not with).  We
#  evaluatoin as an alliance as well as individual teams

create table alliance
 (
  event_id varchar(8),                  # FK to event table (PK)
  alliancenum int,			# Alliance - #1 through #8 (PK)
  locked varchar(12), 			# row locked for editing by user.  Can clear in application.
  offense_analysis text(1000),		# offense analysis (text)
  defense_analysis text(1000), 		# defense analysis (text)
  pos1_analysis text(1000),		# position 1 analysis (text)
  pos2_analysis text(1000),		# position 2 analysis (text)
  pos3_analysis text(1000),		# position 3 analysis (text)
  against_recommendation text(2000),	# recommendation if partnered against
  primary key (event_id, alliancenum)
 );


#
# alliance team
#
# normalized recording of alliance team.  Join to alliance for data
#

create table alliance_team
 (
  event_id varchar(8),                  # FK to event table  (PK)
  alliancenum int,			# Alliance - #1 through #8  (PK)
  teamnum  int, 			# FIRST team number - foreign key from team table  (PK)
  locked varchar(12), 			# row locked for editing by user.  Can clear in application.
  position int,				# position in the alliance (1,2,3)
  primary key (event_id, alliancenum, teamnum)
 );

#
# alliance unavailable -- unavailable for alliance choosing, including 
#  teams that refused us
#
create table alliance_unavailable
 ( 
  event_id varchar(8),                  # FK to event table  (PK)
  alliancenum int,			# Alliance - #1 through #8 (PK)
  teamnum  int, 			# FIRST team number - foreign key from team table
  unavailable boolean,			# marked if team selection is unavailable (refused or otherwise)
  refused boolean,			# refused our offer, so take off the availability list
  primary key (event_id, teamnum)
 );



#########################
#
# Match Tables
#
# Note how match, alliances, and teams are connected to data
#

#
# Match Instance
#
# Master record for any type of match
#
create table match_instance
 (
  event_id varchar(8),          # FK to event table (PK)
  type varchar(1), 		# Q=qualifying, P=practice, F=Final  part of primary key (PK)
                                #   from tBA match_number
  matchnum int,			# match number, part of primary key (PK).  If taken from tBA, decoded (
                                #   from tBA match_number, which looks lik 2010sc_qm20
  locked varchar(12), 		# row locked for editing by user.  Can clear in application.
  updatedby varchar(200), 	# last updated by users
  match_key varchar(5),         # tBA part after _ in match key, e.g. qm20
  final_type varchar(1),	# used in finals: Q=qarter, S=Semi, F=Final
  tba_match_num int,            # tBA match_number
  scheduled_utime int,          # schedule time - unix time
  actual_utime int,             # actual time - unix time
  game_plan text(2000), 	# our game plan for the match.  Note: this is the only field 
				#   that is not match statistics but instead our analysis
  primary key (event_id, type, matchnum)
 );

# could add video
#



#
# Match Instance Alliance
#
#  Scores and other details of a match tied to a given alliance
#
#  Will probably add score breakout mechanism once we determine whether it's useful to see
#
create table match_instance_alliance
 (
  event_id varchar(8),          # FK to event table (PK)
  type varchar(1), 		# Q=qualifying, P=practice, F=Final  part of primary key (PK)
  matchnum int,			# match number, part of primary key (PK)
  color varchar(1),		# R=Red, B=Blue (PK)
  locked varchar(12), 		# row locked for editing by user.  Can clear in application.
  updatedby varchar(200), 	# last updated by users
  score int,			# tBA score, final score
  raw_points int, 		# raw points (prior to penalties)  -- depricate in 2017 if not used and not found in code
  penalty_points int,		# penalty points -- depricate in 2017 if not used and not found in code
  other_points int,		# other points, might need in the future -- depricate in 2017 if not used and not found in code
  seed_points int,		# seed points - seed points in system -- depricate in 2017 if not used and not found in code
  f_score0 varchar(25),          # tBA custom score field
  f_score1 varchar(25),          # tBA custom score field
  f_score2 varchar(25),          # tBA custom score field
  f_score3 varchar(25),          # tBA custom score field
  f_score4 varchar(25),          # tBA custom score field
  f_score5 varchar(25),          # tBA custom score field
  f_score6 varchar(25),          # tBA custom score field
  f_score7 varchar(25),          # tBA custom score field
  f_score8 varchar(25),          # tBA custom score field
  f_score9 varchar(25),          # tBA custom score field
  f_score10 varchar(25),          # tBA custom score field
  f_score11 varchar(25),          # tBA custom score field
  f_score12 varchar(25),          # tBA custom score field
  f_score13 varchar(25),          # tBA custom score field
  f_score14 varchar(25),          # tBA custom score field
  f_score15 varchar(25),          # tBA custom score field
  f_score16 varchar(25),          # tBA custom score field
  f_score17 varchar(25),          # tBA custom score field
  f_score18 varchar(25),          # tBA custom score field
  f_score19 varchar(25),          # tBA custom score field
  f_score20 varchar(25),          # tBA custom score field
  f_score21 varchar(25),          # tBA custom score field
  f_score22 varchar(25),          # tBA custom score field
  f_score23 varchar(25),          # tBA custom score field
  f_score24 varchar(25),          # tBA custom score field
  f_score25 varchar(25),          # tBA custom score field
  f_score26 varchar(25),          # tBA custom score field
  f_score27 varchar(25),          # tBA custom score field
  f_score28 varchar(25),          # tBA custom score field
  f_score29 varchar(25),          # tBA custom score field
  f_score30 varchar(25),          # tBA custom score field
  f_score31 varchar(25),          # tBA custom score field
  f_score32 varchar(25),          # tBA custom score field
  f_score33 varchar(25),          # tBA custom score field
  f_score34 varchar(25),          # tBA custom score field
  f_score35 varchar(25),          # tBA custom score field
  f_score36 varchar(25),          # tBA custom score field
  f_score37 varchar(25),          # tBA custom score field
  f_score38 varchar(25),          # tBA custom score field
  f_score39 varchar(25),          # tBA custom score field
  f_score40 varchar(25),          # tBA custom score field
  f_score41 varchar(25),          # tBA custom score field
  f_score42 varchar(25),          # tBA custom score field
  f_score43 varchar(25),          # tBA custom score field
  f_score44 varchar(25),          # tBA custom score field 
  primary key (event_id, type, matchnum, color)
 );

#
# Match - team
#
# Team entry for match.  6 teams per match
#
# This table is only used in 
# 
create table match_team
 (
  event_id varchar(8),          # FK to event table (PK)
  type varchar(1), 		# foreign key to match_instance table (PK)
  matchnum int,			# match number, foreign key to match_instance table (PK)
  teamnum int,			# team number, foreign key to team table (PK)
  locked varchar(12), 		# row locked for editing by user.  Can clear in application.
  updatedby varchar(200), 	# last updated by users
  color varchar(1),		# R=Red, B=Blue
  position varchar(3),		# position played on field
  rating_offense int,		# 0-9 (9 high) rating on offense strength
  rating_defense int,		# 0-9 (9 high) rating on defense strength
  raw_points int,		# raw points scored
  human_points int,		# human points scored
  penalties int,		# penalty points
  match_notes text(1000),	# Match notes
  match_offense_analysis text(1000),		# offense analysis (text)
  match_defense_analysis text(1000), 	# defense analysis (text)
  match_pos_analysis text(1000),		# position analysis (text)
  match_with_recommendation text(1000),	# recommendation if partnered with
  match_against_recommendation text(1000),	# recommendation if partnered against
  primary key (event_id, type, matchnum, teamnum)
 );


#
# Match - Alliance
#
# Alliance entry for match (one per alliance)
# 
# JLV- Depricate
#
#create table match_alliance_team
# (
#  event_id varchar(8),         # FK to event table (PK)
#  type varchar(1), 		# foreign key to match_instance table (PK)
#  matchnum int,		# match number, foreign key to match_instance table (PK)
#  alliancenum int,		# Alliance - #1 through #8, foreign key to alliance table (PK)
#  locked varchar(12), 		# row locked for editing by user.  Can clear in application.
#  updatedby varchar(200), 	# last updated by users
#  color varchar(1),		# R=Red, B=Blue
#  position varchar(3),		# position played on field
#  rating_offense int,		# 0-9 (9 high) rating on offense strength
#  rating_defense int,		# 0-9 (9 high) rating on defense strength
#  raw_points int,		# raw points scored
#  human_points int,		# human points scored
#  penalties int,		# penalty points
#  primary key (event_id, type, matchnum, alliancenum)
# );


#
# championship listing table
#
# depricate entire table?
#
#create table championteam
#(
#  event_id varchar(8),         # FK to event table 
#  league_name varchar(25),	# long-form of league name
#  teamnum  int, 		# FIRST team number - primary key
#  primary key (event_id, teamnum)
#);

#
# schedule import received from FRIST to be imported
# temporary table used to load data and begin processing
#

create table schedule
 (
  scheduled_utime int, 		# schedule time of match
  type varchar(1),		# match type (see match table) (PK)
  matchnum int,		  	# match number (PK)
  blue1 int,			# blue teamnum
  blue2 int,			# blue teamnum
  blue3 int,			# blue teamnum
  red1 int,			# red teamnum
  red2 int,			# red teamnum
  red3 int,			# red teamnum
  primary key (type, matchnum)
 );
  


#
# Process Lock
#
# Lock table to lock various processes
#  These locks differ from data locks in that they lock processes, such
#  as finals selection from other users. Essentially a process lock 
#  is a database-controlled semphore for use in system and user processes.
#

create table process_lock
 (
   lock_id varchar(20), 	# id of lock in table
   locked varchar(12), 		# row locked for editing by user.  Can clear in application.
   primary key (lock_id)
 );
#
# set up control data in table as part of initialization
#
#
# insert needed locks
insert into process_lock (lock_id) values ('ranking');		# ranking process
insert into process_lock (lock_id) values ('finals_selection');	# ranking process
insert into process_lock (lock_id) values ('custom_param');	# custom_parameter process
insert into process_lock (lock_id) values ('doc_topics');	# topics for docprocess


#
# Messages
#
# Message table to communicate with field
#
# Multiple facilities can use the message table so as not to clobber each other
#

create table message
 (
   facility varchar(20), 	# unique facility using the message table.
   message varchar(200),	# message
   locked varchar(12), 		# row locked for editing by user.  Can clear in application.
   primary key (facility)
 );
#
# set up control data in table as part of initialization
#
#
# insert facilities needed
insert into message (facility) values ('finals_selection');	# finals selection

#
# user profile and preferences
#
create table user_profile
 (
  user varchar(30),		# userid (matches that used for system authentication)
  admin bool,			# admin privilege bit (set/unset)
  matchview varchar(5),		# user preference: match type view preference on matchlist view
  needeval bool,                # user preference: show teams that still need evaluation
  showevaluators bool,          # user preference: show other evaluators in matcheval and matchteameval screens
  primary key (user)
 );


#
# custom_param
#
# customer parameter definitions
#
# Used by the administrator to define custom parameters for a given season.  These 
#  parameters are used to generate .sql files that alter base tables to include
#  the custom parameters, as well as php include structures used to drive the app.
# 
create table custom_param
 (
  tag varchar(20) not null,	# also serves as column name in database, or reported column name on calcs 
  locked varchar(12), 		# row locked for editing by user.  Can clear in application.  May not use.
  updatedby varchar(200), 	# last updated by users
  position int not null,	# order displayed (0 through end)
  used boolean not null,	# used in UI -- may exist in database
  vargroup varchar(10),		# var function (Bot, Match, tBA, or other)
  entrytype varchar(1),		# entry type: D=Direct, C=Calc'd
  dbtype varchar(10),		# database column type
  display varchar(20) not null, # display text in app
  inputlen int,			# input length for field in form
  maxlen int,			# max len of input. Will also set field size in database
  default_value varchar(20),	# default value
  list_of_values varchar(100),	# future feature: list of values in value1,tag1;value2,tag2 format
  db_calc varchar (50),		# SQL format group calculation from match data
  formula_calc varchar (200),	# php formula calclation
  test_avg int,			# test average value - used for test generation
  test_range int,		# test range + or - from average value - used for test generation
  test_values varchar (200),	# comma-separated values to be used in testing varchars and text
  description text(500),	# text description of parameter
  tBA_tag varchar(60),		# tBA_tag from v3 interface
  tBA_type varchar(10),		# tBA_type from v3 interface
  primary key (tag)
 );


#
# stores all documentation
#
create table documentation
 (
   documentation varchar(20),	# title of this page, what the world will see
   topic varchar(20),		# what topic this page falls under, listed under the 'topic' table
   priority int,		# determines the order of the different doc pages under a topic
   locked varchar(12), 		# current editor of this row
   data varchar(5000),		# stores the actual information for this page
   primary key (documentation)
 );

#
# different topics the documentation fits under
# also, add process lock for this table in the process_lock table
#
create table topic
 (
   topic varchar(20),		# title of this category of documentation, the world will see this
   priority int,		# priority of this topic in relation to other topics
   description varchar(200),	# description of the topic, not needed
   primary key (topic)
 );

#
# stores a relationship between a documentation and a page
#
create table pagetodoc
(
   documentation varchar(20),	# title of the documentation
   page varchar(20),		# page the documentation can be accessed by
   primary key (documentation, page)
);

#
# generic system key/value table
#
# Note: use this table to store system values in the database
#
create table system_value
 (
   skey varchar(20),		# key index into values
   value varchar(40),		# value for the key
   primary key (skey)
 );


#
# Blue Alliance API last modified
#
# Stores last modified settings for blue alliance data so that subsequent calls can
#  place the last modified in calling params
#
create table tba_last_modified
 (
  api_call varchar(150),        # API call URL for reference
  last_mod varchar(31),         # tBA last modified returned from tBA
  primary key (api_call)
 );

#
# insert keys needed to start in the system
#
insert into system_value (skey) values ('sys_event_id');		# current event_id used to load Blue Alliance data
insert into system_value (skey) values ('a');		# 

##
## add any other data needed as part of setup
##




