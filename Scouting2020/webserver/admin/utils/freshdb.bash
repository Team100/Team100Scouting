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

echo " Dropping current tables..."
mysql -D ${dbname} -u ${dbuser} --password=${dbpass} \
      < $WEBSRVROOT/database/schema/compsys-tables-drop.sql
echo "    done."

echo " Creating all tables..."
mysql -D ${dbname} -u ${dbuser} --password=${dbpass} \
      < $WEBSRVROOT/database/schema/compsys-tables.sql
echo "    done."

# look for customizations
if [ -e $WEBSRVROOT/database/schema/compsys-customparams.sql ]
then
  echo " Creating modifying tables with customizations..."
  mysql -D ${dbname} -u ${dbuser} --password=${dbpass} \
      < $WEBSRVROOT/database/schema/compsys-customparams.sql 
  echo "    done."
fi


# check for customizations data (as well as .sql definition above)
cnt=0
for custfile in $WEBSRVROOT/database/schema/dump-customizations-*.dmp
do
  if [ "$custfile" = "$WEBSRVROOT/database/schema/dump-customizations-*.dmp" ]
  then continue; fi
  (( cnt++ ))
done

# test for custommization
if [ $cnt -gt 1 ]
then
  echo " More than one customization file was found.  None loaded.  Please load manually."
elif [ $cnt -eq 1 ]
then
  echo " Loading customizations file $custfile... "
  mysql -D ${dbname} -u ${dbuser} --password=${dbpass} \
      < $WEBSRVROOT/database/schema/dump-customizations-*.dmp
  echo "   done."
else
  echo " No customization data loaded."
fi

echo " "
echo "Competition System Tables Built"

#
# end of script
#
