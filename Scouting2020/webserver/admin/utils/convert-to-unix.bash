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
# convert-to-unix file-or-directory
#
# convert single file or all *.bash in a directory
#
# tmp file - used to stage each converted file
tmpfile=convert-to-unix-tmp.$$.tmp

# check if diretory, then cd to file, look for *.bash
if [ -d "$1" ]
then
  cd $1
  files="*.bash"

# single file
elif [ -e "$1" ]
then
  files="$1"

else
  # $1 not actionable
  echo "$1 is not a directory or a file. Exiting"
  exit 1
fi


#####
#
# Begin iteration
#
# inform user of file conversion
for file in $files
do
  if [ "$file" = "*.bash" ]; then continue; fi

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

##
## end
##!
##! DO NOT SAVE this file from WINDOWS
##!
##
