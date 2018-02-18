<?php session_start();
	session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>
		Welcome
	</title>
	<style>
		body {
		background-color: MidnightBlue;
		width: 100%;
		}
		
		#loginHolder {
		position: absolute;
		top: 50%;
		left: 50%;
		margin-top: -188px;
		margin-left: -220px;
		}
	
		#container {
		background-color: GhostWhite;
		border: 1px solid black;
		border-radius: 15px;
		position: relative;
		height: 300px;
		width: 440px;
		box-shadow: 0px 0px 2px black;
		}
		
		#topBorder {
		background-color: DarkSlateBlue;
		width: 100%;
		height: 50%;
		top: 0%;
		left: 0%;
		position: absolute;
		}
		
		#username {
		border: 1px solid grey;
		border-radius: 5px;
		position: absolute;
		top: 36%;
		left: 20%;
		font-size: 20px;
		padding: 5px 5px 5px 5px;
		text-align: center;
		box-shadow: 0px 0px 2px black;
		}
		
		#password {
		border: 1px solid grey;
		border-radius: 5px;
		position: absolute;
		top: 61%;
		left: 20%;
		font-size: 20px;
		padding: 5px 5px 5px 5px;
		text-align: center;
		box-shadow: 0px 0px 2px black;
		}
		
		label {
		font-family: arial;
		font-size: 20px;
		color: black;
		text-shadow: 0px 0px 5px white;
		}
		
		#usernameLabel {
		position: absolute;
		top: 26%;
		left: 38%;
		}
		
		#passwordLabel {
		position: absolute;
		top: 51%;
		left: 38%;
		}
		
		#submitButton {
		background-color: Navy;
		position: absolute;
		top: 80%;
		left: 35%;
		font-size: 20px;
		padding: 7px 30px 7px 30px;
		border-radius: 15px;
		box-shadow: 0px 0px 2px black;
		color: white;
		}
		
		#submitButton:hover {
		background-color: RoyalBlue;
		}
		
		#signInLabel {
		position: absolute;
		top: 7%;
		left: 20%;
		font-size: 35px;
		color: black;
		}
		
		#logoHolder {
		background-color: GhostWhite;
		border: 1px solid black;
		border-radius: 15px;
		position: relative;
		height: 75px;
		width: 440px;
		box-shadow: 0px 0px 2px black;		
		}
		
		#logoLeft {
		position: absolute;
		top: 15%;
		left: 5%;
		height: 50px;
		width: 50px;
		}
		
		#logoLabel {
		position: absolute;
		top: 16%;
		left: 25%;
		font-size: 45px;
		font-family: arial;
		font-weight: bold;
		text-decoration: underline;
		color: Black;
		}
		
		#logoRight {
		position: absolute;
		top: 15%;
		right: 5%;
		height: 50px;
		width: 50px;		
		}
	</style>
</head>
<body>
	
	<!-- when the form is submitted pass the information to the authentication.php file, using post to conceal sensitive data -->
	<form method="post" action="authentication.php" id="login">
		
		<!-- top half of the 'background' -->
		<div id="topBorder"></div>
		
		<!-- holds the actual login 'box' -->
		<div id="loginHolder">
			
			<!-- contains the 'logo' of the company name and logo on either side -->
			<div id="logoHolder">
				<img src="logo.png" id="logoLeft" alt="Logo left">		
				<label for="logo" id="logoLabel">Make-It-All</label>
				<img src="logo.png" id="logoRight" alt="Logo right">
			</div>
			
			<!-- contains the login box forms and associated labels -->
			<div id="container">
				<label id="signInLabel">Helpdesk Sign In</label>
				<label id="usernameLabel" for="username">Username</label>
				<input type="text" name="username" id="username" placeholder="AliceSmith1">
				<label id="passwordLabel" for="password">Password</label>
				<input type="password" name="password" id="password">
				<input type="submit" id="submitButton" value="Log in">
				<?php if($_SESSION['loginError'] == true) echo "Username or Password is incorrect"; ?>
			</div>
		</div>	
		
		<!-- bottom half of the 'background' of the screen -->
		<div id="bottomBorder">
		</div>
	</form>
</body>
</html>
