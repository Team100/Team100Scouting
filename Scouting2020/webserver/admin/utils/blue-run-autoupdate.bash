#!/bin/bash
# 
# Runs blue alliance autoload script periodically
#
# sets up in proper directory and directs output to logfile
#

# variables set up after home vars

## find and set home vars
## using directory of script to call locate-params.bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
. $DIR/locate-params.bash
## should be set up: WEBSRVROOT, ROBOTICSROOT, sitedir, other vars
##   DB: dbname dbuser dbpass dbhost

# docroot - directory from which PHP script should be run
docroot=$WEBSRVROOT/docroot

# logfile
logfile=$WEBSRVROOT/admin/log/autoupdate.log

# phpfile
phpfile=bluealliance-autoupdate.php


# change directory and execute
cd $docroot
php $phpfile >>$logfile 2>&1 

## end
