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
# custom_param
#
# customer parameter definitions
#
# Used by the administrator to define custom parameters for a given season.  These 
#  parameters are used to generate .sql files that alter base tables to include
#  the custom parameters, as well as php include structures used to drive the app.
# 
drop table custom_param;
create table custom_param
 (
  tag varchar(20) not null,	# also serves as column name in database, or reported column name on calcs 
  locked varchar(12), 		# row locked for editing by user.  Can clear in application.  May not use.
  updatedby varchar(200), 	# last updated by users
  position int not null,	# order displayed (0 through end)
  used boolean not null,	# used in UI -- may exist in database
  vargroup varchar(10),		# var function (Bot, Match, or other)
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
  primary key (tag)
 );
  
  


