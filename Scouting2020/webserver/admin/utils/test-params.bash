#!/bin/bash
#
# tests locate-params.bash
#
#

#
# find directory of script to call locate-params.bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
. $DIR/locate-params.bash

# should have set up: "dbname dbuser dbpass dbhost and sitevars list


##
## inform user
##
echo ""
echo "Locale variables:"
echo "  Web server root: $WEBSRVROOT"
echo "  DB Name:         $dbname"
echo "  DB User:         $dbuser"
echo "  DB Password:     $dbpass"
echo "  DB Host:         $dbhost"
echo ""

exit 0
#
# end
#

