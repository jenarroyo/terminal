<!-- /**
* For the brave souls who get this far: You are the chosen ones,
* the valiant knights of programming who toil away, without rest,
* fixing our most awful code. To you, true saviors, kings of men,
* I say this: never gonna give you up, never gonna let you down,
* never gonna run around and desert you. Never gonna make you cry,
* never gonna say goodbye. Never gonna tell a lie and hurt you.

OS Project by Jen Arroyo And Jill Soria
October 2016
 -->

<?php

if(isset($_GET['username']))
{
    $username = urldecode($_GET['username']);
}
else
{
    //FORCE LOGIN PAGE
    header("location: .");
    die();
}

$root_directory = __DIR__. "/root_directory";

$terminal_label = $username . "'s Terminal";
$prompt_label   = $username . '>'; //orig
//$prompt_label = $root_directory . '>'; //overwrite the orig

?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Terminal</title>
  <link href="style.css" media="screen" rel="stylesheet" type="text/css" />
  <script src="jquery-1.10.2.js"></script>
</head>
<body>
  <div class='inner'>
    <!-- TOOLBARS -->
    <div class='toolbar'>
      <ul class='controls'>
        <li>
          <a href="#" class="active"></a>
        </li>
        <li>
          <a href="#" class=""></a>
        </li>
        <li>
          <a href="#" class=""></a>
        </li>
      </ul>
      <div class='title'>
        <!-- Name of user + Terminal -->
        <span class='title-console'> <?php echo $terminal_label; ?></span>
      </div>
    </div>
    <!-- END OF TOOLBARS -->
    <div class="wrapper">
          <!-- This is our screen, where commands and executed commands will be displayed -->
          <div class='' id='screen'></div>

      <div id="command">
        <!-- This is where we type /  input commands -->
        <?php echo $prompt_label;?><input type="text" value="" id="command-line">
      </div>
    </div>
  </div>

  <?php
  include 'footer.php';
  ?>


  <!-- START OF SCRIPT, THIS IS WHERE THE MAGIC HAPPENS -->
  <script>

    var root_directory = '', //fixed
      current_directory = root_directory, //set the root directory as current directory
      display_directory = ''; //for string version of current directory?

    var fileToBeEdited="";
    // var root_directory = "C:\\xampp\\htdocs\\terminal-master\\root_directory",
    //   current_directory = root_directory,
    //   display_directory = "C:\\xampp\\htdocs\\terminal-master\\root_directory\>";

    // Always focus on the commandline
    $(document).ready(function() {
      $("#command-line").focus();
    });

    $('#command-line').keypress(function(e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        //if enter is pressed
        if(keycode == '13'){
          var value = $(this).val();//value of text in commandline

          // Developer's notes :)
          // The .val() get the value from the input field
          // Keycode = 13 is enterbutton
          // Bind keypress event to textbox

          // Code references
          // $("#screen").text("<?php echo $prompt_label;?>" + value); //display value to text screen
          // $("#screen").clone().appendTo("#screen").text("<?php echo $prompt_label;?>" + value); //working using clone() - not advisable
          // $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).prepend($('</div>'))); //working without clone() better because optimized
          // $('#screen').append($('<marquee direction="right">').text(value).prepend($('</marquee>'))); //working marquee command

          //place holders
          var command = '';
          var succeeding_string = '';
          var space_index = value.indexOf(" ");

          // get first word = command, and succeeding string
          // get the index of the first "space" after the first word which is our command
         if (space_index > 0) {
            command = value.substring(0, space_index); //gets the first string
            succeeding_string = value.slice(space_index + 1); //gets the second string
          }
          else {
            command = value; //if no space found, gets all input
          }

          if (command == "") {
            //if the user pressed enter, just show the prompt label
            $("#screen").append($('<div>').text("<?php echo $prompt_label;?>").prepend($('</div>'))); 
          }
          else if (command == "say") {
            command_say(value, succeeding_string);
          }
          else if (command == "marquee") {
            command_marquee(value, succeeding_string);
          }
          else if (command == "cls") {
            command_clear();
          }
          else if (command == "date") {
            command_date(value);
          }
          else if (command == "time") {
            command_time(value);
          }
          else if (command == "datetime") {
            command_datetime(value);
          }
          else if (command == "help") {
            command_help(value);
          }
          else if (command == "exit" || command == "quit") {
            command_exit();
          }
          else if (command == "ls") {//to show list of files
              if(succeeding_string.length>0){
                  invalid(value); //invalid command
              }
              else{
                ls();               
              }
          }  
          else if (command == "rn") {//to rename a file
            rn(succeeding_string);
          }
          else if (command == "mv") {//to move a file
            mv(succeeding_string);
          } 
          else if (command == "rm") {// to remove a file
            rm(succeeding_string);
          }  
          else if (command == "cd") {//to change directory
            cd(succeeding_string);
          } 
          else if (command == "cp") {//to copy a file
            cp(succeeding_string);
          }
          else if (command == "edit") {//to edit a file
            edit(succeeding_string);
          }
          else if (command == "run") {//to run a program file
            run(succeeding_string);
          }
          else if (command == ":wq") {
            save_quit();
          }          
          else {
            invalid(value); //invalid command
          };

          $(this).val(''); //clear textbox after the user presses enter
          $("#screen").scrollTop($("#screen").height()+999900); //makes the screen focus on the latest line
        }

        e.stopPropagation();
        //Stop the event from propogation to other handlers
        //If this line will be removed, then keypress event handler attached at document level will also be triggered

    });

    function command_clear() {
      // cls = clear entire screen
      $('#screen').empty();
    }

    function command_say(value, succeeding_string) {
      // say <string with spaces> = display the string in a new line
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>' + succeeding_string + '</div>'))); //? br for what?
    }

    function command_marquee(value, succeeding_string) {
      // marquee <string with spaces> = display a scrolling string that will move from left to right.
      // you must still be able to enter commands in the terminal.
      // multiple marquee running at the same time is allowed
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><marquee direction="right">' + succeeding_string + '</marquee>')));
    }

    function commandMetadata(command, syntax, description, sample) {
      this.commandName = command;
      this.syntax = syntax;
      this.description = description;
      this.sample = sample;
    }

    //initialization for commands
    var commandsList = []; // array for commands

    var changedir = new commandMetadata("cd", "cd directory", "changes the current directory to the specified directory", "cd directory1");
    var clrscrn = new commandMetadata("cls", "as is", "clear the entire screen", "cls");
    var copy = new commandMetadata("cp", "cp filename, directory", "copies the file to the specified directory or duplicates if file exists in target location", "cp file.txt folder/file.txt");
    var showDate = new commandMetadata("date", "as is", "displays current date.", "date");
    var showDatetime = new commandMetadata("datetime", "as is", "displays current datetime", "datetime");
    var editFileContent = new commandMetadata("edit", "edit filename", "edits the content of the file", "edit file.txt");
    var showList = new commandMetadata("ls", "as is", "displays all contents of the current directory.", "ls");
    var marqueeSomething   = new commandMetadata("marquee  ", "as is", "displays a scrolling string that will move from left to right. ", "marquee OS");
    var moveFile = new commandMetadata("mv", "mv filename, directory", "moves file to the specified directory", "mv file.txt folder/file.txt");
    var removeFile = new commandMetadata("rm", "rm filename", "removes or deletes the specified file.", "rm file.txt");
    var renameFile = new commandMetadata("rn", "rn oldfile newfile", "renames the file or extension or both", "rn file.txt file2.txt");
    var runFile = new commandMetadata("run", "run cprogram", "runs the c program", "run cprogram");
    var saySomething = new commandMetadata("say", "say any", "display the string in a new line", "say any");
    var showTime = new commandMetadata("time", "as is", "displays current time", "time");
    var quit = new commandMetadata("exit", "as is", "exit console, back to login page", "exit");


    //populate commandsList
    commandsList.push(changedir);
    commandsList.push(clrscrn);
    commandsList.push(copy);
    commandsList.push(showDate);
    commandsList.push(showDatetime);
    commandsList.push(editFileContent);
    commandsList.push(showList);
    commandsList.push(marqueeSomething  );
    commandsList.push(moveFile);
    commandsList.push(removeFile);
    commandsList.push(renameFile);
    commandsList.push(runFile);
    commandsList.push(saySomething);
    commandsList.push(showTime);
    commandsList.push(quit);


    function command_help(value) {
      //display the list of commands for the user to use
      $('#screen').append('<div> <?php echo $prompt_label;?>' + 'help ' + '</div>');
      
      //print headers
      var html = "<div style='clear:both'>" +
              "<div style='float:left;width:100px'>Command</div>" +
              "<div style='float:left;width:250px'>Syntax</div>" +
              "<div style='float:left;width:600px'>Description</div>" +
              "<div style='float:left;width:200px'>Sample Code</div>" +
              "<br /></div>";
          $('#screen').append(html);

      //print commands
      jQuery.each(commandsList, function(index, command) {
        html = "<div style='clear:both'>" +
          "<div style='float:left;width:100px'>" + command.commandName + "</div>" +
          "<div style='float:left;width:250px'>" + command.syntax + "</div>" +
          "<div style='float:left;width:600px'>" + command.description + "</div>" +
          "<div style='float:left;width:200px'>" + command.sample + "</div>" +
          "<br /></div>";
        $('#screen').append(html);
      });
    }

    function command_date(value) { //displays the date
      var d = new Date();
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>' + d.toDateString() + ' </div>')));
    }

    function command_time(value) { //displays the time
      var time_string = make_time();
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>' + time_string + '</div>')));
    }

    function command_datetime(value) { //displays the datetime
      var time_string = make_time();
      var d = new Date();
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>' + d.toDateString() + ' ' + time_string + ' </div>')));
    }

    function command_exit() { //shows the login page
      window.location.href = 'index.php';
    }

    function invalid(value) {
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>invalid or unrecognizable command "' + value + '"</div>')));
    }

    //Evidently, JS doesn't have built in AM/PM and friendly time functions so I have to make these two time helper functions
    function make_time()
    {
        var d = new Date();
        var hours = d.getHours();
        var mins = time_pad(d.getMinutes());
        var secs = time_pad(d.getSeconds());
        var ampm = 'am';
        if(hours > 12)
        {
            hours = hours - 12;
            ampm = 'pm'
        }

        return hours + ':' + mins + ':' + secs + ampm;
    }

    function time_pad(i) {
        if(i < 10) return "0" + i;
        else return i;
    }
    
    // ADDITIONAL FUNCTIONS FOR MP02

    function setCurrentDirectory(directory) {
      if (directory != "..") {
        //Subdirectory
        if (directory != root_directory) {
          current_directory += directory + "/";
          display_directory = directory;
        }
        else {
          //Root directory
          current_directory = root_directory;
          display_directory = ""; //orig
        }
      }
      else{
        //Move one folder up
        var folders = current_directory.split("/");
        folders.splice(-2, 2); //negative means position at the end of the array
        display_directory = folders.slice(-1,1);
        current_directory = folders.join("/"); //The join() method joins the elements of an array into a string, and returns the string.
      }
    }

    function ls(){

      var directory = current_directory; //transfer current directory to directory variable
      

          /*** 
           * @TODO: If calling ls inside a subdirectory, need to determine current directory
           * Save current directory when doing a cd (change directory)
           *
           * @TODO ls with parameters ex. ls -l
           * Restriction, current code does not accept multiple params as of yet...ata..
           *
           * @TODO: When creating a file. make sure to change permissions to 777
           ***/

      //Ajax call to get list of file of current directory from server-side
      $.ajax({
        type: 'POST',
        url: '/terminal-master/ls.php',
        //dataType: "html",
        dataType: "json",
        data: {"directory": directory},
        success: function(result)
        {
          $('#screen').append('<div> <?php echo $prompt_label;?>' + display_directory + '>ls ' + display_directory + '</div>'); //change it to display instead of directory.
          //$('#screen').append("<pre>" + result + "</pre><br/>");

          //print headers
          var html = "<div style='clear:both; width:100%'>" +
              "<div style='float:left;width:150px'>Name</div>" +
              //"<div style='float:left;width:150px'>Owner</div>" +
              "<div style='float:left;width:80px'>Size</div>" +
              "<div style='float:left;width:80px'>%</div>" +
              "<div style='float:left;width:200px'>Created</div>" +
              "<div style='float:left;width:200px'>Modified</div>" +
              "<br></div>";
          $('#screen').append(html);

          var contentCount = 0 ;
          var diskUsedSpace = 0;
          var diskFreeSpace = 0;
          var folderSize = 0;
          //print file content
          jQuery.each(result, function(index, file) 
          {
            //if there are no files in the directory, just get the 2 info needed:
            if(file.name==undefined) {
              diskUsedSpace = result[0];
              diskFreeSpace = result[1];
              folderSize = result[2];
            }
            else {
              html = "<div style='clear:both; width:100%'>" +
                "<div style='float:left;width:150px'>" + file.name + "</div>" +
                //"<div style='float:left;width:150px'>" + file.owner + "</div>" +
                "<div style='float:left;width:80px'>" + file.size +"</div>" +
                "<div style='float:left;width:80px'>" + file.percentage + "</div>" +
                "<div style='float:left;width:200px'>" + file.created + "</div>" +
                "<div style='float:left;width:200px'>" + file.modified + "</div>" +
                "<br></div>";
              $('#screen').append(html);

              contentCount++;
              diskUsedSpace = file.diskUsedSpace;
              diskFreeSpace = file.diskFreeSpace;
              folderSize = file.folderSize;
            }
          }); //jQuery Each

          var diskSizeInfo = "<div style='float:left;width:25%'>&nbsp;</div><div align=left style='float:left;width:75%'> <div>" + contentCount + " Item(s) \t Size: "  + folderSize + "  </div> <div> " + diskUsedSpace + " bytes used </div><div>" + diskFreeSpace + " bytes free </div>" +
            "</div>";
          $('#screen').append(diskSizeInfo);
        }
      });
    }


    //Used to edit file name
    //@TODO: Used to edit extension
    //@TODO: Used to move the file
    function rn(arguments) {

      //need to be revised since what if the first file has a space
      /*
      var files = arguments.split(" "), 
        new_file,
        old_file;
      */

      var files = [];
      var firstDotIndex = arguments.indexOf(".");
      var partial = arguments.substring(firstDotIndex);
      var oldfile = arguments.substring(0, firstDotIndex) + partial.substring(0, partial.indexOf(" "));
      var newfile = partial.substring(partial.indexOf(" ")+1);

      files.push(oldfile);
      files.push(newfile);


      $('#screen').append('<div style="clear:both"> <?php echo $prompt_label;?> ' + display_directory + '>rn ' + arguments + '</div>');
      if (files.length == 2) {

        $.ajax({
          type: 'POST',
          url: '/terminal-master/rn.php',
          //dataType: "html",
          dataType: "json",
          data: {
            'currentDirectory': current_directory,
            "old_file": files[0],
            "new_file": files[1]
          },
          success: function(success) {

            var message;

            if (success===true) {
              message = "Successfully renamed file/directory";
            } 
            else if(success===false) {
              message = "Rename file/directory failed";
            }
            else if(success== -2) {
              message = "There is already a file with the same name in this location. A duplicate file is created instead.";
            }
            else if(success== -3) {
              message = "Source file does not exist. A new file is created instead.";
            }

            $('#screen').append('<div style="clear:both">' + message + '</div>');
          }
          
        });
      }
      else {
        $('#screen').append('<div style="clear:both"> Usage: rn &lt;old-file-name&gt; &lt;new-file-name&gt;</div>');
      }
    }

    //move a file
     function mv(arguments) {
      $('#screen').append('<div style="clear:both"> <?php echo $prompt_label;?> ' + display_directory + '>mv ' + arguments + '</div>');

      var data = [];
      //convert all slashes in arguments to be /
      arguments = arguments.replace(/\\/g,"/"); //g for gloabl = all instances

      var divider = arguments.match("\\.[A-Za-z]{1,4}\\s[\\w\\/]+"); //

      if(divider!=null){
          var delimiter = divider[0].replace(" ", " /");
          //correct the arguments:
          arguments = arguments.replace(divider, delimiter);
          data = arguments.split(" /");
      }
      else {
        //go to else error below

      }

      if (data.length == 2) {
        //get file to be moved:
        var fileTobeMoved = data[0].substring(data[0].lastIndexOf("/")+1);
        if(fileTobeMoved.match("[^*|\"<>?]+\.[A-Za-z]{1,4}")){
          data.push(fileTobeMoved);

          $.ajax({
          type: 'POST',
          url: '/terminal-master/mv.php',
          //dataType: "html",
          dataType: "json",
          data: {
            "currentDirectory": current_directory,
            "sourceDirFile": data[0],
            "destDirFile": data[1],
            "file": data[2],
          },
          success: function(success) {

            var message;

            if (success===true || success===1) {
              message = "Successfully moved file";
            } 
            else if(success===false) {
              message = "Moving of file failed. Please check both source and destination paths.";
            }
            else if(success == -2) {
              message = "Moving of file failed. There is no file specified in the source parameter.";
            }
            else if(success == -3) {
              message = "Moving of file failed. Source file does not exist.";
            }
            else if(success == -4) {
              message = "Moving of file failed. Target destination does not exist.";
            }
            else if(success == -5) {
              message = "Moving of file failed. There is no file specified in the destination parameter.";
            }
            else if(success == -6) {
              message = "There is already a file with the same name in the target location. A duplicate file is created instead.";
            }

            $('#screen').append('<div style="clear:both">' + message + '</div>');
          }
          
          }); //ajax
        }
        else {
          $('#screen').append('<div style="clear:both"> Invalid filename. Please make sure that you entered the syntax correctly: mv &lt;source dir/file&gt; &lt;dest dir/file&gt;</div>');
        }
      }
      else {
        $('#screen').append('<div style="clear:both"> Usage: mv &lt;file-path&gt; &lt;new-file-path&gt;</div>');
      }
    }


    //Remove file/directory
    function rm(file) 
    {
      $('#screen').append('<div style="clear:both"> <?php echo $prompt_label;?> ' + display_directory + '>rm ' + file + '</div>');

      if (file != "") {

        $.ajax({
          type: 'POST',
          url: '/terminal-master/rm.php',
          //dataType: "html",
          dataType: "json",
          data: {"file": file, 'directory': current_directory},
          success: function(success) {
            var message;
            
            if (success ===true) {
              message = "Successfully deleted file.";
            }
            else if (success === false) {
              message = "Delete failed";
            }
            else if (success == 2) {
              message = "Successfully deleted directory.";
            }

            $('#screen').append('<div style="clear:both">' + message + '</div>');
          }
          
        });
      }
      else 
      {
        $('#screen').append('<div style="clear:both"> Please provide file to be deleted </div>');
      }
    }

    function cd(directory){ //directory passed here is the succeeding string

      $('#screen').append('<div style="clear:both"> <?php echo $prompt_label;?>' + display_directory + '>cd ' + directory + '</div>');
      $.ajax({
          type: 'POST',
          url: '/terminal-master/cd.php',
          //dataType: "html",
          dataType: "json",
          data: {'newDirectory': directory, 
                 "currentDirectory": current_directory},
          success: function(success) {

            var message;

            if (!success) {
              message = directory + " is not a directory";
              $('#screen').append('<div style="clear:both">' + message + '</div>');
            } 
            else {
              setCurrentDirectory(directory);
            }
          } //success function
        }); //ajax call
    }

    // The File manager should be able to duplicate a file in the same directory 
      // and the name would immediately have a (1) or (2) or “Copy of” prefix.
    function cp(arguments) { 

      $('#screen').append('<div style="clear:both"> <?php echo $prompt_label;?> ' + display_directory + '> cp ' + arguments + '</div>');
      var data = [];
      //convert all slashes in arguments to be /
      arguments = arguments.replace(/\\/g,"/"); //g for gloabl = all instances

      var divider = arguments.match("\\.[A-Za-z]{1,4}\\s[\\w\\/]+"); //

      if(divider!=null){
          var delimiter = divider[0].replace(" ", " /");
          //correct the arguments:
          arguments = arguments.replace(divider, delimiter);
          data = arguments.split(" /");
      }
      else {
        //directory is being copied.
        data = arguments.split(" "); //delikads, what if folder name has space.
      }


      if (data.length == 2) {
        //get file to be copied:
        var fileTobeCopied = data[0].substring(data[0].lastIndexOf("/")+1);
        if(fileTobeCopied.match("[^*|\"<>?]+\.[A-Za-z]{1,4}")){
          data.push(fileTobeCopied);
        }

        $.ajax({
          type: 'POST',
          url: '/terminal-master/cp.php',
          //dataType: "html",
          dataType: "json",
          data: {
            "currentDirectory": current_directory,
            "sourceDirFile": data[0],
            "destDirFile": data[1],
            "file": data[2],
          },
          success: function(success) {

            var message;

            if (success===true || success==1) {
              message = "Successfully copied file";
            } 
            else if(success===false) {
              message = "Copying of file failed. Please check both source and destination paths.";
            }
            else if(success == -2) {
              message = "Copying failed. Copying of directory is not allowed!";
            }
            else if(success == -3) {
              message = "Copying of file failed. Source to be copied does not exist!";
            }
            else if(success == -4) {
              message = "Copying of file failed. Target Destination is invalid.";
            }
            else if(success == -5) {
              message = "Successfully copied the directory.";
            }
            else if(success == -6) {
              message = "Copying failed. Usage cp filename destination/filename";
            }

            $('#screen').append('<div style="clear:both">' + message + '</div>');
          }
          
        });
      }
      else {
        $('#screen').append('<div style="clear:both"> Usage: cp &lt;source dir/file&gt; &lt;dest dir/file&gt;</div>');
      }
    }

    function edit(file){
      $('#screen').append('<div style="clear:both"> <?php echo $prompt_label;?> ' + display_directory + '> edit ' + file + '</div>');
      //set the mode = command
      var mode = 1; //command 0 for insert/edit
      fileToBeEdited = file;

      //Ajax call to get file contents
      $.ajax({
        type: 'POST',
        url: '/terminal-master/edit.php',
        //dataType: "html",
        dataType: "json",
        data: {"currentDirectory": current_directory, "file": file, "mode" : mode},
        success: function(fileContents)
        {
          
          //then show contents
          if(fileContents===""){
              $('#screen').append('<div style="clear:both"> File does not exist! </div>');
          }
          else {
            $('#screen').empty(); //clear the screen
            var html = "<div style='clear:both'>" +
                "<div style='float:left;width:150px'><textarea name='' id='edit_textarea' cols='100' rows='20'>" + fileContents + "</textarea></div>" +
                "</div>";
            $('#screen').append(html);

            $("#edit_textarea").focus();
          }
        }
      });
    }

    function run(file){      
      //First is to get the path of the file or the file must be in the same directory
      $('#screen').append('<div style="clear:both"> <?php echo $prompt_label;?> ' + display_directory + '>run ' + file + '</div>');

      //if file is not empty then process
      if (file != ""){
        $.ajax({
          type: 'POST',
          url: '/terminal-master/run.php',
          //dataType: "html",
          dataType: "json",
          data: {"file": file, 'currentDirectory': current_directory},
          success: function(success) {
            var message;

            if (success ===true) {
              message = "Successfully run file.";
            }
            else if (success === false) {
              message = "Run failed. File is invalid or does not exist.";
            }

            $('#screen').append('<div style="clear:both">' + message + '</div>');
          }
          
        });
      }
      else 
      {
        $('#screen').append('<div style="clear:both"> Please provide file to be run </div>');
      }
     }

function save_quit(){
  //get the data from the text box
  var fileContents = $('#edit_textarea').val();
  //set the mode = command
  var mode = 3; //command 0 for insert/edit //3 for save

  //Ajax call to get file contents
  $.ajax({
    type: 'POST',
    url: '/terminal-master/edit.php',
    //dataType: "html",
    dataType: "json",
    data: {"currentDirectory": current_directory, "file": fileToBeEdited, "mode" : mode, "content": fileContents},
    success: function(success)
    {          
      if(fileContents>0){
        $('#screen').append('<div style="clear:both"> Updating file is successful.</div>');
      }
      else {
        $('#screen').empty(); //clear the screen

        $("#command-line").focus();
      }
    }
  });
};
    
    </script>

<!--     // ADDITIONAL FUNCTIONS FOR FINAL PROJECT
    // I.CLI Should Display the following
    //   1. Current files in the active directory   
    //   2. Information of each files in the active directory
    //       a. Data Created ->OK
    //       b. Date Modified ->OK
    //       c. Owner
    //       d. File Size   ->OK

          // e. EXTRA CREDITS
          //     1. extra credit if file size is measured in the most applicable unit.
          //         e.g. if file is 1024KB then display 1MB -> OK
          //     2. Display how much space in the disk has been occupied and how much are unused.
          //     3. Display the percentage of space in the hard disk that is being consumed by each file.

    // II. The File Manager should be able to:
    //   1. Edit the File Name   -> OK
    //   2. Edit the extension   -> OK
    //   3. Move the file (to a new directory)
    //   4. Copy the file (to a new directory only)
    //   5. Delete the file -> OK

   // III. Should be able to run certain C program that will be provided by the instructor.
   //  IV. (Extra Credit) The File manager should be able to edit the contents of the file inside the console itself 
   //      (without opening an external file). This is similar to the VI used by Unix-based systems 
   //      (learn more from here: www.ccsf.edu/Pub/Fac/vi.html ). Upon editing, the updated date modified and file size 
   //       should be seen when the contents of the directory are being displayed. 
    // V. (Extra Credit) The File manager should be able to duplicate a file in the same directory and the name would immediately have a (1) or (2) or “Copy of” prefix. -->


</body>
</html>


