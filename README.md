# terminal
<!--
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

 -->