<?php
session_start();
include("db.php");
mysqli_report(MYSQLI_REPORT_OFF);
$pagename="sign up results"; //Create and populate a variable called $pagename
echo "<link rel=stylesheet type=text/css href=mystylesheet.css>"; //Call in stylesheet
echo "<title>".$pagename."</title>"; //display name of the page as window title
echo "<body>";
include ("headfile.html"); //include header layout file 
echo "<h4>".$pagename."</h4>"; //display name of the page on the web page

//Capture and trim the 7 inputs entered in the the 7 fields of the form using the $_POST superglobal variable
//Store these details into a set of 7 new local variables

$fname=trim($_POST['firstname']);
$lname=trim($_POST['lastname']);	
$address=trim($_POST['address']);
$postcode=trim($_POST['postcode']);
$telno=trim($_POST['telno']);
$email=trim($_POST['email']);
$password1=trim($_POST['password1']);


//Write a SQL query to insert a new user into Users table
$SQL="insert into users (userType, userFName, userSName, userAddress, userPostCode, userTelNo, userEmail, userPassword)
values ('C','".$fname."','".$lname."','".$address."','".$postcode."', '".$telno."', '".$email."', '".$password1."')";
//Execute the INSERT INTO SQL query
mysqli_query($conn, $SQL);

//create a regular expression variable
$reg = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";

if (empty($fname) or empty($lname) or empty($address) or empty($postcode) or empty($telno) or empty($email) or
empty($password1))
{
	echo "<p><b>Sign-up failed!</b></p>";
	echo "<br><p> Your Signup form is incomplete and all fields are mandotory";
	echo "<br>Make sure you provide all the required details</p>";
	echo "<br><p>Go back to <a href=signup.php>sign up</a></p>";
	echo "<br><br><br><br>";
}
elseif (!preg_match($reg, $email))
{
	echo "<p><b> sign-up failed!</b></p>";
	echo "<br><p>Email not valid";
	echo "<br>Make sure you enter a correct email address</p>";
	echo "<br><p>Go back to <a href=signup.php>sign up</a></p>";
	echo "<br><br><br><br>";
}
else
{
	$SQL="insert into Users
	(userType, userFName, userSName, userAddress, userPostCode, userTelNo, userEmail, userPassword)
	values
	('C','".$fname."','".$lname."','".$address."','".$postcode."','".$telno."','".$email."', '".$password1."')";
	//if SQL execution is correct
if (mysqli_query($conn, $SQL))
{
//display sign up success
echo "<p><b>Sign-up successful!</b></p>";
echo "<br><p>To continue, please <a href=login.php>login</a></p>";
}
//if the SQL execution is erroneous, there is a problem
else
{
	//display sign up failure
	echo "<p><b>Sign-up failed!</b></p>";

	//echo "<br><p>SQL Error No: ".mysqli_errno($conn)."</p>"; //Retrieve the error number and display.
	//echo "<p>SQL Error Msg: ".mysqli_error($conn)."</p>"; //Retrieve the error message and display.

	//if the error detector returned the number 1062,

	//the unique constraint is violated as the user entered an email which already existed
	if (mysqli_errno($conn)==1062)
{
	echo "<br><p>Email already in use";
	echo "<br>You may be already registered or try another email address</p>";
}

//error code for inserting character that is problematic for SQL query
if (mysqli_errno($conn)==1064)
{
	echo "<br><p>Invalid characters entered in the form";
	$invalidchars="apostrophes like [ ' ] and backslashes like [ \ ]";
	echo "<br>Make sure you avoid the following characters: ".$invalidchars. "</p>";
}
echo "<br><p>Go back to <a href=signup.php>sign up</a></p>";
echo "<br><br><br><br>";
}
}
include("footfile.html"); //include head layout
echo "</body>";
