#!/bin/bash
#
# performs steps to refresh install
#  - sets php parameters for site environment
#  - installs .htaccess
#
#  uses site variable in $sitedir.def
#
#

# param setup
paramsfilename=params.php
tmpfile=/tmp/robotics-repl-params.$$

## find and set home vars
## using directory of script to call locate-params.bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
. $DIR/locate-params.bash
## should be set up: WEBSRVROOT, ROBOTICSROOT, sitedir, other vars
##   DB: dbname dbuser dbpass dbhost

##
## set up replacement list
##
echo -n "Replacing params in $paramsfile..."
for param in $sitevars
do
  cp -p $WEBSRVROOT/docroot/params.inc $tmpfile
  sed -e "s/^\$$param =.*/\$$param = \"${!param}\";/" $tmpfile \
   > $WEBSRVROOT/docroot/params.inc 
done
echo done.


##
## locate .htaccess file and process if needed
##
##  user instance-specific source.htaccess file if exists, otherwise
##   customize instance-general file for this installation with $WEBSRVROOT
##
##  for htmaster, perform same test
## 
echo "Refreshing .htaccess..."

htaccessloc=$WEBSRVROOT/docroot/.htaccess
accinstspec=$WEBSRVROOT/admin/instance-specific/source.htaccess
accinstgen=$WEBSRVROOT/admin/instance-general/source.htaccess
mastinstspec=$WEBSRVROOT/admin/instance-specific/htpasswd.master
mastinstgen=$WEBSRVROOT/admin/instance-general/htpasswd.master

# find htmaster
if [ -e $mastinstspec ]
then
  htpasswd=$mastinstspec
elif [ -e $mastinstgen ]
then
  htpasswd=$mastinstgen
else
  echo "  Cannot find htpasswd.master file in"
  echo "   $mastinstspec or"
  echo "   $mastinstgen."
  echo "   Skipping .htaccess setup"
  htpasswd=""  
fi
  
# find right one htaccess
if [ "$htpasswd" != "" ]
then
  if [ -e $accinstspec ]
  then
    cp $accinstspec $htaccessloc
    echo "  Used $accinstspec for .htaccess"
    echo "done."
  elif [ -e $accinstgen ]
  then
    sed -e 's;\$htmasterpath;'$htpasswd';' $accinstgen > $htaccessloc
    echo "  Used $accinstgen for .htaccess"
    echo "done."
  else
    echo "  Cannot find source.htaccess in"
    echo "  $accinstspec"
    echo "   of $accinstgen."
    echo "   Skipping .htaccess setup" 
  fi
fi


#
# clean up tmp
rm -rf $tmpfile


exit 0
#
# end
#

