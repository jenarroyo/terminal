# terminal

Dev notes in console.php:

Accomplishments ao 11/26/2016:
1. Enhanced the ls function to handle ls + succeeding word = invalid command

2. Changed the myOS to prompt in the different methods in screen append

3.added the ff empty functions:
  mv - move
  cp - copy

4. improved the output of the help function by:
  adding an array to which the display will loop thru, created a commandmetadata class and instantiated several commands. put them on the array commandList created.

For improvements:
dynamic prompt to reflect current directory -> entails changes in processing the directory

error in cd: going in a subfolder

not working functions for windows:
rename
remove


Accomplishments ao 11/27/2016:
Added percentage in size unit display
Added percentage in ls display
Added the following in LS display: show total items, disk usage and disk free space data
enhanced failed delete message

Accomplishments ao 11/28/2016:
fixed the bug in displaying disk size: shows undefined if current directory has no content
Fix: total to used; added an if else to show the disk usage even if current directory is empty.
removed some clutters;
added useful info in ls for the getting of directory and other comments
added an extra data in ajax call to cd to enable change of directories in subdirectories.
commented the function current_directory, bec it is conflict with variable current_directory

Accomplishments ao 11/30/2016:
copying of file is now a functionality (and duplicate!):
	-includes parsing and regex to determing 2 parameters (source, target)
	converts all \ to /
intelligence: adds a slash if the dest param doesnt have one at the start. this is to enforce all params have slash at the start for destinations
detects the following and corresponding error messages to the user:
if file to be copied is existing
if target destination is valid
if source param is a directory
if dest param is a directory


Accomplishments ao 12/04/2016:

modified cd.php to be simpler in terms of logic. removed the file checking and edited the ajax call in console
FIX: renaming something that has an existing name already -> create a new duplicate and delete old file
FIX: renaming something that doesnt exist -> create new file
added a date modification change after renaming a file or creating a duplicate.
FIX: change date modified in renaming a file
FIX: changed the logic of copy to have incremental numbers (applicable to rename also when file is existing)
new function : move. used renaming as base function
added current directory file size display
new functionality: create a new file for rename when source file does not exist
improved logic on cp.php by handling different scenarios. same as mv.php.
enhanced messaging to indicate what really happened in all functions (e.g. rm)

pending tasks:
show of transaction status after commands
deleting a directory
copying a directory
editing a file
running a c program