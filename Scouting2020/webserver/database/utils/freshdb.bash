#/bin/bash
#
#
# clears database and loads a fresh schema, upload tables, etc.
#

## find and set home vars
## using directory of script to call locate-params.bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
. $DIR/locate-params.bash
## should be set up: WEBSRVROOT, ROBOTICSROOT, sitedir, other vars
##   DB: dbname dbuser dbpass dbhost

## main
echo "Building Competition System Tables..."

echo "  Dropping current tables..."
mysql -D ${dbname} -u ${dbuser} --password=${dbpass} \
      < $WEBSRVROOT/database/schema/compsystem-tables-drop.sql
echo "    done."

echo "  Creating all tables..."
mysql -D ${dbname} -u ${dbuser} --password=${dbpass} \
      < $WEBSRVROOT/database/schema/compsystem-tables.sql
echo "    done."
echo " "
echo "Competition System Tables Built"

#
# end of script
#