#!/bin/bash
#
# This script should be included via a "." command in scripts determining
#  local parameters.  It is not intended to be called stand alone.
#
# Locates $filename or site-instance-specific.def file in the following priority:


# if $sitefilename is not null, locates that file as a first option with the sites.def
# file being used if $filename does not exist in each diretory.
#  Otherwise determines site instance from filesystem name above the
#  webserver root.  Example: site instance name stage
#  comes from /home/www/robotics/stage.
#
# Priority order:
#  - file exists in overall sitesdef directory
#  - file exists in instance admin/instance-specific directory
#  - file exists in general directory admin/instance-general
#      a general file will usually work for the multiple sites
#
#  - in each diretory, look for the $searchname dir first,
#     then the $sitedefname file, then in the general and specific instance
#     directories, the sitespec.def file.
#

# find directory of script
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
#echo "Script directory: $DIR"

# look for sitedefs directory name two above the directory of this script, 
sitedir=`echo $DIR | sed -e 's;/[^/]*/[^/]*$;;' -e 's;^.*/;;'`
roboticsroot=`echo $DIR | sed -e 's;/[^/]*/[^/]*/[^/]*$;;'`
#
# set automatically here, but can be an override for local instance
WEBSRVROOT=$roboticsroot/$sitedir

#echo "Site dir: $sitedir"
#echo "Robotics home: $roboticsroot"

#
sitedefname=$sitedir.def
# determine file target to search for
if [ "$sitefilename" = "" ]; then searchname=$sitedefname;
  else searchname=$sitefilename; fi
#echo "Filename: $searchname"

#
# search for file in series of directories.  Source if found

# if general sitesdef filename
if [ -e $roboticsroot/sitedefs/$searchname ]
then
  . $roboticsroot/sitedefs/$searchname
# if general sitesdef sitedir    
elif [ -e $roboticsroot/sitedefs/$sitedefname ]
then
  . $roboticsroot/sitedefs/$sitedefname
# instance-specific searchname
elif [ -e $WEBSRVROOT/admin/instance-specific/$searchname ]
then
    . $WEBSRVROOT/admin/instance-specific/$searchname ]
# instance-specific sitedir							       
elif [ -e $WEBSRVROOT/admin/instance-specific/$sitedefname ]
then
    . $WEBSRVROOT/admin/instance-specific/$sitedefname ]
# instance-specific sitespec.def
elif [ -e $WEBSRVROOT/admin/instance-specific/sitespec.def ]
then
    . $WEBSRVROOT/admin/instance-specific/sitespec.def ]
# instance-general searchname
elif [ -e $WEBSRVROOT/admin/instance-general/$searchname ]
then
    . $WEBSRVROOT/admin/instance-general/$searchname ]
# instance-general sitedir
elif [ -e $WEBSRVROOT/admin/instance-general/$sitedefname ]
then
    . $WEBSRVROOT/admin/instance-general/$sitedefname ]
# instance-general sitespec.def
elif [ -e $WEBSRVROOT/admin/instance-general/sitespec.def ]
then
    . $WEBSRVROOT/admin/instance-general/sitespec.def ]
else
  echo "Error: could not find $searchname or $sitedefname in any directory"
  echo "       in the search path for site parameters.  Existing."
  exit 1
fi

