#!/bin/bash
#
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
#!
#! DO NOT SAVE this file from WINDOWS
#!
#!
#! This file must not be converted to a windows file which ends lines with
#! ^J^M.  The file will not be able to be read in Linux.  This file
#! converts other utilities so they can be used on Linux.
#
#!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
#
#

# don't touch:
# find directory of script
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
cd $DIR

##
## variables
# directorties to be converted, all relative to the directory in which
#  this script is kept) ==> 
files="*.bash */*.bash */*/*.bash ../database/*.bash ../database/*/*.bash"
files="$files ../database/*/*/*.bash"
files="$files *.def */*.def */*/*.def ../database/*.def ../database/*/*.def"
files="$files ../database/*/*/*.def"

# tmp file - used to stage each converted file
tmpfile=START_SCRIPT_staging_file.$$.tmp

#####
#
# Begin
#
# inform user of file conversion
echo "Converting all bash files from Windows to Unix..."

for file in $files
do
  if [ "$file" = "START_SCRIPT.bash" ]; then continue; fi

  # if file exists, convert
  if [ -e $file ]
  then
    echo -n "Converting $file to unix..."
    cp -p $file $tmpfile
    tr -d '\15\32' < $tmpfile > $file
    chmod +x $file
    echo done.
  fi
done

rm -f $tmpfile
echo "...done."

#
# copy locate-params.bash into database/utils
echo " "
echo -n "Copying utils/locate/params.bash to database/utils..."
if [ -e utils/locate-params.bash ]
then
  cp utils/locate-params.bash ../database/utils
  echo "done."
else       
  echo "WARNING: could not find utils/locate-params.bash.  The system needs this file"
  echo "          for install.  Please repair."
fi
    
#
# end with instructions for next steps

cat <<EOF

Starting setup complete.  Some possible next steps:
  - utils/refresh-install: refreshes and resets parameters
  - convert-from-windows.bash: converts file to be able to 
      be run on Linux.

New install:
  - see install instructions in doc/CompSys-WebServ-Install.txt
    for various utilities that help in setup.

EOF

##
## end
##!
##! DO NOT SAVE this file from WINDOWS
##!
##
