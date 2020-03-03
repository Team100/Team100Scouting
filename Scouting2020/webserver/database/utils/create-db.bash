#!/bin/bash
#
# creates MySQL database and sets up robotics account
#
#  locates params file, then uses params to creat db
#
#

## find and set home vars
## using directory of script to call locate-params.bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
. $DIR/locate-params.bash
## should be set up: WEBSRVROOT, ROBOTICSROOT, sitedir, other vars
##   DB: dbname dbuser dbpass dbhost

##
## inform user
##
echo ""
echo "Robotics MySQL Database Creation and Setup"
echo "------------------------------------------"
echo ""
echo "Will set up the following database:"
echo "  DB Name:     $dbname"
echo "  DB User:     $dbuser"
echo "  DB Password: $dbpass"
echo "  DB Host:     $dbhost"
echo ""
echo "** Root database access should have previously be granted."
echo "** You will need the root database password many times to proceed."
echo " "
echo ""
echo -n "Enter 'Y' to continue >"

read inp
if [ "$inp" != "Y" ] ; then exit; fi

# collect root password
echo " "
echo -n "Enter database root password >"
read passwd


# create db
echo ""
echo "Creating database $dbname..."
mysqladmin create $dbname -u root --password=$passwd 
echo "...done."
echo ""

# grant access
echo "Creating user..."
mysql -D $dbname -u root --password=$passwd <<EOF
create user '$dbuser' identified by '$dbpass';
flush privileges;
EOF

echo "...done."
echo ""


echo "Creating usage rights..."
mysql -D $dbname -u root --password=$passwd <<EOF
show databases;
grant usage on *.* to '$dbuser'@localhost identified by '$dbpass';
grant all on $dbname.* to '$dbuser'@localhost;
flush privileges;
EOF

echo "...done."
echo ""



exit 0
#
# end
#

