rem
rem $Revision: 3.0 $
rem $Date: 2016/03/14 23:00:02 $
rem
rem clears database and loads a fresh schema, upload tables, etc.
rem

set dbname=competition
set dbuser=compuser
set dbpass=100hats

mysql -D competition -u compuser --password=%dbpass% < ..\schema\compsys-tables-drop.sql

mysql -D %dbname% -u %dbuser% --password=%dbpass% < ..\schema\compsys-tables.sql

mysql -D %dbname% -u %dbuser% --password=%dbpass% < ..\schema\compsys-customparams.sql

rem load customization

mysql -D %dbname% -u %dbuser% --password=%dbpass% < ..\schema\dump-customizations-*.dmp

rem insert documentation

rem fresh database complete