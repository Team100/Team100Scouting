#/bin/bash
#
# refresh-from-github
#
# refreshes source from github, then copies files over existing
#  distribution

# variables set up after home vars

## find and set home vars
## using directory of script to call locate-params.bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
. $DIR/locate-params.bash
## should be set up: WEBSRVROOT, ROBOTICSROOT, sitedir, other vars
##   DB: dbname dbuser dbpass dbhost

# overall home -- home directory underwhich various directories contain 
#   components of system
home=/home/www/robotics

# git websrvroot
gitwebsrvroot=$ROBOTICSROOT/github/Scouting2020/webserver


#
# Top of Commands
#
# Inform user as we progress
#

echo " " 
echo "Updates github repo dir to lastest master and installs in website"
echo " " 
echo " "

# update github
echo "Updating github..."

cd $gitrepo
git fetch origin master
git reset --hard FETCH_HEAD
git clean -df

echo "...done."
echo " " 

# copy git repo to htdocs and admin
echo -n "Copying from refreshed source to website directories..."
cp -LTr $gitwebsrvroot $WEBSRVROOT
echo "done"
echo " "

# run START and refresh-install
echo "Running START_HERE and refresh-install to update..."
. $WEBSRVROOT/admin/START_HERE.bash
. $WEBSRVROOT/admin/utils/refresh-install.bash
echo " ...done."

echo " " 
echo "Refresh and install complete."

exit

