rem
rem $Revision: 3.0 $
rem $Date: 2016/03/14 23:00:02 $
rem
rem Dumps documentation to be loaded on other databases
rem

set dbname=competition
set dbuser=compuser
set dbpass=100hats

rem dump options
rem  --complete-insert: form complete insert statements with col names
rem  --extended-insert: use multirow inserts that speed up load
rem  --no-create-db:    don't create the db
rem  --no-create-info:  don't create tables 
set options=--complete-insert --no-create-db --extended-insert --no-create-info

set tables=docnode documentation pagetodoc

set today=%date:~10,4%-%date:~4,2%-%date:~7,2%

mysqldump %options% -u %dbuser% --password=%dbpass% %dbname% %tables% > ..\dumps\dump-doc-%today%.dmp
 
