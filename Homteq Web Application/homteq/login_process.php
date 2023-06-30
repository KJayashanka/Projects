<?php
session_start();
include("db.php");
$pagename="your login results"; //Create and populate a variable called $pagename
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; //Call in stylesheet
echo "<title>".$pagename."</title>"; //display name of the page as window title
echo "<body>";

include ("headfile.html"); //include header layout file 
echo "<h4>".$pagename."</h4>"; //display name of the page on the web page

//capture the 2 values entered by the user in the form using the $_POST superglobal variable
//assign these 2 values to 2 local variables
$email = $_POST['l_email'];
$password = $_POST['l_password'];
//display the content of the local variables to make sure the values are passed properly
echo "<p>Login email: ".$email."</p>";
echo "<p>Login pwd: ".$password."</p>";

include("footfile.html"); //include head layout
echo "</body>";
