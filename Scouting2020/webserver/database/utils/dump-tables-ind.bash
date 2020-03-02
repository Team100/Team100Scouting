#!/bin/bash
#
# performs dump of mysql DB tables
#  usage: dump-db.bash [-q]
#   -q: run in quiet mode without user output
#


## find and set home vars
## using directory of script to call locate-params.bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
. $DIR/locate-params.bash
## should be set up: WEBSRVROOT, ROBOTICSROOT, sitedir, other vars
##   DB: dbname dbuser dbpass dbhost

# vars to drive export
tables="teambot match_instance match_instance_alliance match_team"
dumpsdir=$WEBSRVROOT/database/dumps
date=`date '+%y%m%d%H%M'`

# dump options
#  --complete-insert: form complete insert statements with col names
#  --extended-insert: use multirow inserts that speed up load
#  --no-create-db:    don't create the db
#  --no-create-info:  don't create tables (not used)
options=" --complete-insert --no-create-db --extended-insert"


#
# begin: inform user
#
if [ "$1" != "-q" ]
then
  echo " " 
  echo "Robotics Competition System Tables Export"
  echo "  Database:   $dbname"
fi

# loop through tables and dump each
for table in $tables
do
  mysqldump $options --user=$dbuser --password=$dbpass $dbname $table \
   > $dumpsdir/dump-$table.$date.dmp
  if [ "$1" != "-q" ]; then echo "   Dumped $table."; fi
done		     

if [ "$1" != "-q" ]
then
 echo "Export complete."
fi

exit 0
## end
