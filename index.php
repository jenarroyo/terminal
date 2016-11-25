<?php
/*
<!-- * For the brave souls who get this far: You are the chosen ones,
* the valiant knights of programming who toil away, without rest,
* fixing our most awful code. To you, true saviors, kings of men,
* I say this: never gonna give you up, never gonna let you down,
* never gonna run around and desert you. Never gonna make you cry,
* never gonna say goodbye. Never gonna tell a lie and hurt you.

OS Project by Jen Arroyo And Jill Soria
October 2016


2016-1021: UPDATES by jenarroyo
1.) All html files now PHP to be able to do some very minor but necessary server-side processing (all old html files in "oldfiles" folder)

2.) login page: action is now index.php, method is post. 
username is passed to console.php as parameter through GET, urlencoded. 
fixed input type of textbox to be "text" instead of name, added "name" attribute with a value of "Name". Fixed CSS that previously targeted input type=Name to input type="text"

3.)console.php
Now accepts username from GET, urldecodes it.
terminal_label variable is used to display the terminal label, uses username as part of the string.
prompt_label variable is used to display the prompt label, uses username as part of the string.
Added commands "date", "time" and "datetime", which displays the current date, current time, and current date + time, respectively.
Added the new commands description to help command.
 -->
*/
if(isset($_POST['Name']) && $_POST['btnSubmit'])
{
    $username = urlencode($_POST['Name']);
    header("location: console.php?username=$username");
    die();
}

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
            <title>Terminal</title>
            <link href="style.css" media="screen" rel="stylesheet" type="text/css"/>
            <script src="jquery-1.10.2.js"></script>
    </head>
    <body>
        <div id="name" align="center">
            <h2><span class=""></span>Who are you?</h2>
            <form action="index.php" method="post">
                <fieldset>
                    <p><input type="text" name="Name" id="Name" value="" placeholder="username"></p>
                    <p><input type="submit" name="btnSubmit" value="Submit"></p>
                </fieldset>
            </form>
        </div>

        <?php
        include 'footer.php';
        ?>

        <script>
          // Focus cursor to the name textbox
          $(document).ready(function(){$("#Name").focus();});
        </script>
    </body>
</html>
