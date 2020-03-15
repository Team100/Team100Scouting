#!/bin/bash
#
# generates documentation for internal sofware documents
#
#


## find and set home vars
## using directory of script to call locate-params.bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
. $DIR/locate-params.bash
## should be set up: WEBSRVROOT, ROBOTICSROOT, sitedir, other vars
##   DB: dbname dbuser dbpass dbhost

# variables:
dochome=$WEBSRVROOT/admin/doc/softwareInternals
srchome=$WEBSRVROOT/docroot

##
## inform user
##
echo ""
echo "Generating internal documentation..."
echo "  Page functions"
grep '^//' $srchome/page.inc > $dochome/page-functions.txt


echo "  Blue Alliance"
grep '^//' $srchome/bluealliance.inc > $dochome/blue-alliance-functions.txt


echo "...done."
echo " "

exit 0
#
# end
#

