<?php 
//start a session allowing session variables to be created and passed, utilised when 'retaining' logged in user
session_start();
    
//include the file which contains the database username and password to allow secure access
include "team11-mysql-connect.php";

//declare all database variables used to connect to the database
$dbhost = 'localhost';
$dbname = 'team11project';

//make a connection to the database, based on above variables, and assign this connection to a variable
$conn = mysqli_connect($dbhost, $username, $password, $dbname); 

//ensure the connection is made by creating an error message if the connection fails
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//retrieve the input username and password from login.php and assign to variables
$oldPasswordValue = $_POST['oldPassword'];
$newPasswordValue = $_POST['newPassword'];
$usernameValue = $_POST['currentUser']; 

//create an SQL statement to retrieve the password from the database based on the input username
$sql="SELECT Pass FROM Login WHERE user = '".$usernameValue."'"; 

//assign the result of the SQL query on the database to a variable
$result = mysqli_query($conn, $sql);

//format the result of the SQL in a way that allows information to be used, by row
$row = mysqli_fetch_assoc($result);

//assign the password row, should be the only row returned and only one result should be obtained, to a variable
$correctPassword = $row["Pass"];

//check that the SQL query returned at least one result, otherwise username does not exist
if ($result->num_rows > 0) 
{
	
	//if a result is returned check the returned, 'correct', password against the user input password
	if($correctPassword == $oldPasswordValue)
	{
		
		//if the passwords match then redirect user to the main page, the session variable is used to ensure only a logged in user may access the page, currently not working
		$sql = "UPDATE Login SET Pass = '".$newPasswordValue."' WHERE user = '".$usernameValue."'";
		mysqli_query($conn, $sql);
		$_SESSION['authenticated'] = true;
		$_SESSION['user'] = $usernameValue;
		header('location: index.php');
	}
	
	//if the password input does not match the password returned based on the username redirect the user to the login page
	else
	{
		$_SESSION['authenticated'] = true;
		$_SESSION['user'] = $usernameValue;
		header('location: index.php');
	}
}

//if no rows are returned, username is incorrect or does not exist, return the user to the login page
else
{
	$_SESSION['authenticated'] = true;
	$_SESSION['user'] = $usernameValue;
	header('location: index.php');
}

//close the connection with the database
mysqli_close($conn);
?>