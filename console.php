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

$terminal_label = $username . "'s Terminal";
$prompt_label   = $username . '>';

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
    // Always focus on the commandline
    $(document).ready(function()
    {
      $("#command-line").focus();
    });

    $('#command-line').keypress(function(e)
    {
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

          var command = '';
          var succeeding_string = '';
          var space_index = value.indexOf(" ");

          // get first word = command, and succeeding string
          // get the index of the first "space" after the first word which is our command
         if (space_index > 0)
          {
            command = value.substring(0, space_index);
            succeeding_string = value.slice(space_index + 1);
          }
          else
          {
            command = value;
          }

          if (command == "")
          {
            $("#screen").append($('<div>').text("<?php echo $prompt_label;?>").prepend($('</div>')));
          }
          else if (command == "say")
          {
            command_say(value, succeeding_string);
          }
          else if (command == "marquee")
          {
            command_marquee(value, succeeding_string);
          }
          else if (command == "cls")
          {
            command_clear();
          }
          else if (command == "date")
          {
            command_date(value);
          }
          else if (command == "time")
          {
            command_time(value);
          }
          else if (command == "datetime")
          {
            command_datetime(value);
          }
          else if (command == "help")
          {
            command_help(value);
          }
          else if (command == "exit")
          {
            command_exit();
          }
          else if (command == "ls")
          {
            ls(succeeding_string);
          }  
          else if (command == "rn")
          {
            rn(succeeding_string);
          }  
          else if (command == "rm")
          {
            rm(succeeding_string);
          }      
          else
          {
            invalid(value);
          };

          $(this).val(''); //clear textbox
          $("#screen").scrollTop($("#screen").height()+9000);
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
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>' + succeeding_string + '</div>')));
    }

    function command_marquee(value, succeeding_string) {
      // marquee <string with spaces> = display a scrolling string that will move from left to right.
      // you must still be able to enter commands in the terminal.
      // multiple marquee running at the same time is allowed
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><marquee direction="right">' + succeeding_string + '</marquee>')));
    }

    function command_help(value) {
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>').html("Here are the list of valid commands you can use.<br/><br/> cls &emsp;&emsp;&emsp;&emsp;-clear the entire screen.<br/> date &emsp;&emsp;&emsp; -displays current date. <br/> datetime&emsp; -displays current datetime. <br/> marquee&emsp;&emsp;-display scrolling string that will move from right to left. <br/> say&emsp;&emsp;&emsp;&emsp; -display the string in a new line.<br/>time&emsp;&emsp;&emsp; -displays current time. <br/>exit&emsp;&emsp;&emsp; -exit console, back to login page.")));
    }

    function command_date(value) {
      var d = new Date();
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>' + d.toDateString() + ' </div>')));
    }

    function command_time(value) {
      var time_string = make_time();
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>' + time_string + '</div>')));
    }

    function command_datetime(value) {
      var time_string = make_time();
      var d = new Date();
      $('#screen').append($('<div>').text("<?php echo $prompt_label;?>" + value).append($('</div><br/><div>' + d.toDateString() + ' ' + time_string + ' </div>')));
    }

    function command_exit() {
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

    function ls(directory){

          /*** 
           * @TODO: If calling ls inside a subdirectory, need to determine current directory
           * Save current directory when doing a cd (change directory)
           *
           * @TODO ls with parameters ex. ls -l
           * Restriction, current code does not accept multiple params as of yet...ata..
           *
           * @TODO: When creating a file. make sure to change permissions to 777
           ***/

      console.log("directory: " + directory);

      //Ajax call to get list of file of current directory from server-side
      $.ajax({
        type: 'POST',
        url: '/ls.php',
        //dataType: "html",
        dataType: "json",
        data: {"directory": directory},
        success: function(result) {
          $('#screen').append('<div> MyOS>ls ' + directory + '</div>');
          //$('#screen').append("<pre>" + result + "</pre><br/>");

          //print headers
          var html = "<div style='clear:both'>" +
              "<div style='float:left;width:150px'>Name</div>" +
              // "<div style='float:left;width:150px'>Owner</div>" +
              "<div style='float:left;width:80px'>Size</div>" +
              "<div style='float:left;width:200px'>Created</div>" +
              "<div style='float:left;width:200px'>Modified</div>" +
              "<br /></div>";
          $('#screen').append(html);

          //print file content
          jQuery.each(result, function(index, file) {
            html = "<div style='clear:both'>" +
              "<div style='float:left;width:150px'>" + file.name + "</div>" +
              // "<div style='float:left;width:150px'>" + file.owner + "</div>" +
              "<div style='float:left;width:80px'>" + file.size + "</div>" +
              "<div style='float:left;width:200px'>" + file.created + "</div>" +
              "<div style='float:left;width:200px'>" + file.modified + "</div>" +
              "<br /></div>";
            $('#screen').append(html);
          });
        }
      });
    }


    //Used to edit file name
    //@TODO: Used to edit extension
    //@TODO: Used to move the file
    function rn(arguments) {

      var files = arguments.split(" "),
        new_file,
        old_file;

      $('#screen').append('<div style="clear:both">MyOS> rn ' + arguments + '</div>');

      if (files.length == 2) {

        $.ajax({
          type: 'POST',
          url: '/rn.php',
          //dataType: "html",
          dataType: "json",
          data: {
            "old_file": files[0],
            "new_file": files[1]
          },
          success: function(success) {

            var message;

            if (success) {
              message = "Successfully renamed file/directory";
            } else {
              message = "Rename file/directory failed";
            }

            $('#screen').append('<div style="clear:both">' + message + '</div>');
          }
          
        });
      }
      else {
        $('#screen').append('<div style="clear:both"> Usage: rn &lt;old-file-name&gt; &lt;new-file-name&gt;</div>');
      }
    }

    //Remove file/directory
    function rm(file) {


      $('#screen').append('<div style="clear:both">MyOS> rm ' + file + '</div>');

      if (file != "") {

        $.ajax({
          type: 'POST',
          url: '/rm.php',
          //dataType: "html",
          dataType: "json",
          data: {"file": file},
          success: function(success) {

            var message;

            if (success) {
              message = "Successfully deleted file/directory";
            } else {
              message = "Delete failed";
            }

            $('#screen').append('<div style="clear:both">' + message + '</div>');
          }
          
        });
      }
      else {
        $('#screen').append('<div style="clear:both"> Please provide file to be deleted </div>');
      }
    }



    function current_directory(){
      // //current directory
      // echo getcwd() . "\n";

      // chdir('cvs');

      // //current directory
      // echo getcwd() . "\n";

    }

    function file_info(){
      // display all file info
    }

    function file_info_date_created(){

    }

    function file_info_date_modified() {

    }

    function file_info_owner() {

    }

    function file_info_file_size() {

    }


    function edit_filename(){

    }

    function edit_fileextension(){

    }

    function move_file_new_directory(){

    }

    function copy_file_new_directory(){

    }

    function delete_file(){

    }

    function duplicate_file(){
      // The File manager should be able to duplicate a file in the same directory 
      // and the name would immediately have a (1) or (2) or “Copy of” prefix.

    }

    function edit_content(){

    }

    </script>

<!--     // ADDITIONAL FUNCTIONS FOR FINAL PROJECT
    // I.CLI Should Display the following
    //   1. Current files in the active directory
    //   2. Information of each files in the active directory
    //       a. Data Created
    //       b. Date Modified
    //       c. Owner
    //       d. File Size

          // e. EXTRA CREDITS
          //     1. extra credit if file size is measured in the most applicable unit.
          //         e.g. if file is 1024KB then display 1MB 
          //     2. Display how much space in the disk has been occupied and how much are unused
          //     3. Display the percentage of space in the hard disk that is being consumed by each file.

    // II. The File Manager should be able to:
    //   1. Edit the File Name
    //   2. Edit the extension
    //   3. Move the file (to a new directory)
    //   4. Copy the file (to a new directory only)
    //   5. Delete the file

   // III. Should be able to run certain C program that will be provided by the instructor.
   //  IV. (Extra Credit) The File manager should be able to edit the contents of the file inside the console itself 
   //      (without opening an external file). This is similar to the VI used by Unix-based systems 
   //      (learn more from here: www.ccsf.edu/Pub/Fac/vi.html ). Upon editing, the updated date modified and file size 
   //       should be seen when the contents of the directory are being displayed. 
    // V. (Extra Credit) The File manager should be able to duplicate a file in the same directory and the name would immediately have a (1) or (2) or “Copy of” prefix. -->
</body>
</html>


