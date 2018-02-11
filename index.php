<?php
	
	//My attempt at stopping people directly type the URL to access the page, doesn't work properly yet
	session_start();
	//include 'authentication.php';
	if (!isset($_SESSION['authenticated']))
	{
		header('Location: login.php');
		exit;
	}
	else {}
?>
<html>
    <head>
        <script>
            
            //Switches the focused section to the correct page when a tab is selected, uses the selected tab as parameter to determine displayed page
			function openTab(event, tabName, type) {
                var i, tabContent, mainTab;
                if(type == "main"){
                    tabContent = document.getElementsByClassName("tabContent");
                    mainTab = document.getElementsByClassName("mainTab");
                }else if(type =="sub"){
                    tabContent = document.getElementsByClassName("subTabContent");
                    mainTab = document.getElementsByClassName("subTab");
                }
                for(i = 0; i < tabContent.length; i++){
                    tabContent[i].style.display = "none";
                }
                for(i = 0; i < mainTab.length; i++){
                    mainTab[i].className = mainTab[i].className.replace(" active", "");
                }
                document.getElementById(tabName).style.display = "inline";
                event.currentTarget.className += " active";
            }
			
			//redirects the user to the login page when the logout option is selected in the user/profile drop down menu
			function logout() {
				header("location: login.php");
			}
			
			//will, when completed, take the user to/allow them to edit username/password, will utilise the username input when logging in to determine which username/password to change
			function userProfile() {
			
			}
			
			//causes the, initially hidden, drop down menu to appear when the user icon is clicked, hides the menu when the icon is clicked again
			function dropDownMenu() {
				document.getElementById("userDropDownMenu").classList.toggle("visible");
			}

			//should cause the drop down menu to close if anywhere on the page is clicked while it is open, didn't work on first try and haven't reattempted, will do soon
			/* window.onclick = function(event) {
				if (!event.target.matches('#userIcon')) {
					var dropdowns = document.getElementsByClassName("dropdown-content");
					var i;
					for (i = 0; i < dropdowns.length; i++) {
						var openDropdown = dropdowns[i];
							if (openDropdown.classList.contains('show')) {
							openDropdown.classList.remove('show');
							}
					}
				}
			} */
	
        </script>
        <style>
			body {
			width: 100%;
			}
			
			.container {
			height: 93%;
			}
			
            div.subMenu {
                float: left;
                border: 1px solid black;
                background-color: #f1f1f1;
                width: 100%;
                height: 10%;
            }
            div.subMenu span {
                display: inline-block;
                background-color: inherit;
                color: black;
                padding: 22px 16px;
                width: 30%;
                height: 35%;
                text-align: center;
                cursor: pointer;
            }
            div.subMenu span:hover {
                background-color: #ddd;
            }
            #divider {
                position: relative;
                height:5%;
                display: block;
            }
            div.mainMenu {
                float: left;
                border: 1px solid black;
                background-color: #f1f1f1;
                width: 15%;
                height: 100%;
            }
            div.mainMenu span {
                display: block;
                background-color: inherit;
                color: black;
                padding: 22px 16px;
                width: 84%;
                text-align: left;
                cursor: pointer;
            }
            div.mainMenu div {
                display: block;
                background-color: inherit;
                color: black;
                padding: 22px 16px;
                width: 80%;
                text-align: left;
                cursor: pointer;
            }
            div.mainMenu span:hover {
                background-color: #ddd;
            }
            div.mainMenu span.active {
                background-color: #ccc;
            }
            /* Style the tab content */
            .tabContent {
                float: left;
                padding: 0px 12px;
                border: 1px solid black;
                width: 82%;
                border-left: none;
                height: 100%;
                display:none;
            }
			
			#headerBar {
			border: 1px solid black;
			height: 44px;
			width: 98.9%;
			background-color: #f1f1f1;
			}
			
			#headerBarLabel {
			padding: 13px 1px 13px 1px;
			width: 200px;
			position: relative;
			font-size: 100%;
			border-right: 1px solid black;
			text-align: center;
			}
			
			#mainImage {
			cursor: default;
			border-bottom: 1px solid black;
			width: 84%;
			}
			
			#userIcon {
			position: absolute;
			height: 38px;
			width: 38px;
			cursor: pointer;
			}
			
			#userDropDownHolder {
			position: absolute;
			display: inline-block;
			top: 13px;
			left: 96%;
			}
			
			.userDropDownContent {
			position: relative;
			display: none;
			top: 44px;
			left: -70px;
			box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			background-color: #f9f9f9;
			min-width: 120px;
			}
			
			div.dropDownOption:hover {
			background-color: #f1f1f1;
			cursor: pointer;
			}
			
			div.dropDownOption {
			text-align: center;
			}			
						
			.visible {
			display: block;
			}
			
        </style>
    </head>
	<body>
        
		<!-- div which contains elements of the header bar including the 'title', user icon as well as the drop down menu form the user icon -->
		<div id="headerBar">
			<div id="headerBarLabel">Team 11 Helpdesk Prototype</div>
			
			<!-- holds the drop down menu items including, initally hidden, options as well as the icon to 'activate' the menu -->
			<div id="userDropDownHolder">
				<img id="userIcon" src="userIcon.png" onclick="dropDownMenu()">
				
				<!-- initially invisible div which holds the options for the user menu, when the icon is clicked the div is given a visible display attribute -->
				<div id="userDropDownMenu" class="userDropDownContent">
					
					<!-- when working the top of the user drop down menu should display the currently 'logged in' user based on entered login details, doesn't currently work -->
					<div value ="<? echo '$username' ?>"></div>
					<div class="dropDownOption" onclick="userProfile()">Profile</div>
					<div class="dropDownOption" onclick="logout()">Logout</div>
				</div>
			</div>
		</div>
		<div class='container'>
            <div class="mainMenu">
                <div id="mainImage"><img src="logo.png" alt="Make It All" style="padding:10px; max-width:88%; max-height:88%;"></div>
                <span class="mainTab active" onclick="openTab(event, 'home', 'main')">Home</span>
                <span class="mainTab" onclick="openTab(event, 'newProblem', 'main');openTab(event, 'requiredInfo', 'sub');">New Problem</span>
                <span class="mainTab" onclick="openTab(event, 'problemsList', 'main')">Problems List</span>
                <span class="mainTab" onclick="openTab(event, 'specialistsList', 'main')">Specialists List</span>
                <span class="mainTab" onclick="openTab(event, 'hardSoftWareList', 'main')">Hardware/Software List</span>
                <span class="mainTab" onclick="openTab(event, 'analytics', 'main')">Analytics</span>
            </div>

            <div id="home" class="tabContent">
                <h3>Home</h3>
                Welcome to The Heldesk Prototype.
            </div>
            <div id="newProblem" class="tabContent">
                <div class="subMenu">
                    <span class="subTab active" onclick="openTab(event, 'requiredInfo', 'sub')">Required Information</span>
                    <span class="subTab" onclick="openTab(event, 'additionalInfo', 'sub')">Additional Information</span>
                    <span class="subTab" onclick="openTab(event, 'descriptionLog', 'sub')">Problem Description / Log</span>
                </div>
                <h3>New Problem</h3>
                New Problem form here.
                <div id="requiredInfo" class="subTabContent">
                    Required Information form goes here.
                    <p onclick="openTab(event, 'additionalInfo', 'sub');"> Next button takes you to next form/tab</p>
                </div>

                <div id="additionalInfo" class="subTabContent">
                    Additional Information form goes here.
                    <p onclick="openTab(event, 'descriptionLog', 'sub');">Next button takes you to next form/tab</p>
                </div>

                <div id="descriptionLog" class="subTabContent">
                    Problem Description and Log area form goes here.
                    <p onclick="alert('form submitted, hopefully.');">Submit button submits the form.</p>
                </div>
                <script>openTab(event, 'requiredInfo', 'sub');</script>
            </div>

            <div id="problemsList" class="tabContent">
                <h3 onclick="openTab(event, 'viewProb', 'sub')">Problems List</h3>
                Problems list table here.

                <div class="subTabContent" id="viewProb" style="display:none;">Hello there.</div>

            </div>

            <div id="specialistsList" class="tabContent">
                <h3>Specialists List</h3>
                Specialists table here.
            </div>

            <div id="hardSoftWareList" class="tabContent">
                <h3>Hardware List</h3>
                Hardware Table here.
                <h3>Software List</h3>
                Software Table here.
            </div>

            <div id="analytics" class="tabContent">
                <h3>Analytics</h3>
                Potentially some analytics here.
            </div>
        </div>
        <script>openTab(event, 'home', 'main');</script>
    </body>

</html>