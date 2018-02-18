<?php

//open a session to allow session variables to be created, passed and retrieved
session_start();

//Checks whether the $_SESSION variable 'authenticated' has been assigned anything, if the user is logged in it should be set to 'true', if not redirect to the login page
if (!isset($_SESSION['authenticated']))
{
    header('Location: login.php');
    exit;
}
else {}

//assign the session variable retrieved in the login page, the username entered, to a variable to be used for displaying logged in user
$user = $_SESSION['user'];


$passwordChangeAttempt = $_SESSION['passwordChangeAttempt'];

//assign the session variable passwordChanged, retrieved from the passwordChange.php password changing file, to passwordChanged variable
//will either be true or false if the password was or wasn't changed, depending on which an error message/alert box should display indicating which event happened/didn't happen
$passwordChanged = $_SESSION['passwordChanged'];

//destroy the session, deleting session variables, this way any refresh or navigate to the page will redirect unless the user comes from the correct file, login.php	
session_destroy();
?>
<html>
    <head>
        <title>T11 Helpdesk</title>
        <link rel="icon" href="logo.png">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
        <script>
            function filterTable(i) {
                var input, filter, table, tr, td, i;
                input = document.getElementById(i+'Search');
                filter = input.value.toUpperCase();
                table = document.getElementById(i+'Table');
                tr = table.getElementsByTagName("tr");
                for (x = 1; x < tr.length; x++) {
                    tr[x].style.display = "none";
                }
                var str = i + 'Table';
                for (j = 0; j < document.getElementById(str).rows[0].cells.length; j++){
                    // Loop through all table rows, and hide those who don't match the search query
                    for (i = 1; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[j];
                        if (td) {
                            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            }
                        }
                    }
                }
            }

            function sortTable(n, i) {
                var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
                table = document.getElementById(i);
                switching = true;
                //Set the sorting direction to ascending:
                dir = "asc";
                /*Make a loop that will continue until no switching has been done:*/
                while (switching) {
                    //start by saying: no switching is done:
                    switching = false;
                    rows = table.getElementsByTagName("tr");
                    /*Loop through all table rows (except the first, which contains table headers):*/
                    for (i = 1; i < (rows.length - 1); i++) {
                        //start by saying there should be no switching:
                        shouldSwitch = false;
                        /*Get the two elements you want to compare,	one from current row and one from the next:*/
                        x = rows[i].getElementsByTagName("td")[n];
                        y = rows[i + 1].getElementsByTagName("td")[n];
                        /*check if the two rows should switch place, based on the direction, asc or desc:*/
                        if (dir == "asc") {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                //if so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        } else if (dir == "desc") {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                //if so, mark as a switch and break the loop:
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }
                    if (shouldSwitch) {
                        /*If a switch has been marked, make the switch and mark that a switch has been done:*/
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                        //Each time a switch is done, increase this count by 1:
                        switchcount++;
                    } else {
                        /*If no switching has been done AND the direction is "asc", set the direction to "desc" and run the while loop again.*/
                        if (switchcount == 0 && dir == "asc") {
                            dir = "desc";
                            switching = true;
                        }
                    }
                }
            }

            //Gets a string containing a html table, which it then displays, depending on which tab is open (the parameter).
            function getTable(type) {
                $(document).ready(function() {
                    $.get("getTable.php", {'type':type}, function(data) {
                        document.getElementById(type+"Div").innerHTML = data;
                    })
                }(jQuery))
            }

            var software = [];
            var information = [];           

            function useSoftTable() {
                $(document).ready(function() {
                    $.get("getSoftTable.php", function(data) {
                        information = [];
                        software = [];			
                        var obj = JSON.parse(data);
                        var length = obj.length;
                        var indexes = [];
                        var startDate;
                        var endDate;
                        var difference;
                        for(var x =0;x<length;x++){
                            if(indexes.includes(obj[x].SoftwareID)){
                                startDate = new Date((obj[x].CallDateTime.substring(0,4)),(obj[x].CallDateTime.substring(5,7)-1),(obj[x].CallDateTime.substring(8,10)),(obj[x].CallDateTime.substring(11,13)),(obj[x].CallDateTime.substring(14,16)),(obj[x].CallDateTime.substring(17,19)));
                                endDate = new Date((obj[x].DateTimeSolved.substring(0,4)),(obj[x].DateTimeSolved.substring(5,7)-1),(obj[x].DateTimeSolved.substring(8,10)),(obj[x].DateTimeSolved.substring(11,13)),(obj[x].DateTimeSolved.substring(14,16)),(obj[x].DateTimeSolved.substring(17,19)));
                                difference=(endDate-startDate)/1000;
                                information[x]=difference;
                            }else{
                                indexes.push(obj[x].SoftwareID);
                                software[x] = obj[x].SoftwareName;
                                startDate = new Date((obj[x].CallDateTime.substring(0,4)),(obj[x].CallDateTime.substring(5,7)-1),(obj[x].CallDateTime.substring(8,10)),(obj[x].CallDateTime.substring(11,13)),(obj[x].CallDateTime.substring(14,16)),(obj[x].CallDateTime.substring(17,19)));
                                endDate = new Date((obj[x].DateTimeSolved.substring(0,4)),(obj[x].DateTimeSolved.substring(5,7)-1),(obj[x].DateTimeSolved.substring(8,10)),(obj[x].DateTimeSolved.substring(11,13)),(obj[x].DateTimeSolved.substring(14,16)),(obj[x].DateTimeSolved.substring(17,19)));
                                difference=(endDate-startDate)/1000;
                                information[x]=difference;
                            }
                        }
                    })
                    var ctx = document.getElementById("softwareChart").getContext('2d');
                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: software,
                            datasets: [{
                                label: "Software Time to Solve (seconds)",
                                backgroundColor: 'rgb(0,0,0)',
                                borderCoolor: 'rgb(0,0,0)',
                                data: information,
                            }]
                        },
                        options: {
                            scales:{
                                yAxes:[{
                                    ticks:{
                                        suggestedMin: 0,
                                    }
                                }]
                            }
                        }
                    });
                }(jQuery))
            }

            var hardware = [];
            var informationH = [];           
            function useHardTable() {
                $(document).ready(function() {
                    $.get("getHardTable.php", function(data) {
                        hardware = [];
                        informationH = [];					
                        var obj = JSON.parse(data);
                        var length = obj.length;
                        var indexes = [];
                        var startDate;
                        var endDate;
                        var difference;
                        for(var x =0;x<length;x++){
                            if(indexes.includes(obj[x].HardwareID)){
                                startDate = new Date((obj[x].CallDateTime.substring(0,4)),(obj[x].CallDateTime.substring(5,7)-1),(obj[x].CallDateTime.substring(8,10)),(obj[x].CallDateTime.substring(11,13)),(obj[x].CallDateTime.substring(14,16)),(obj[x].CallDateTime.substring(17,19)));
                                endDate = new Date((obj[x].DateTimeSolved.substring(0,4)),(obj[x].DateTimeSolved.substring(5,7)-1),(obj[x].DateTimeSolved.substring(8,10)),(obj[x].DateTimeSolved.substring(11,13)),(obj[x].DateTimeSolved.substring(14,16)),(obj[x].DateTimeSolved.substring(17,19)));
                                difference=(endDate-startDate)/1000;
                                informationH[x]=difference;
                            }else{
                                indexes.push(obj[x].HardwareID);
                                hardware[x] = obj[x].HardwareType;
                                startDate = new Date((obj[x].CallDateTime.substring(0,4)),(obj[x].CallDateTime.substring(5,7)-1),(obj[x].CallDateTime.substring(8,10)),(obj[x].CallDateTime.substring(11,13)),(obj[x].CallDateTime.substring(14,16)),(obj[x].CallDateTime.substring(17,19)));
                                endDate = new Date((obj[x].DateTimeSolved.substring(0,4)),(obj[x].DateTimeSolved.substring(5,7)-1),(obj[x].DateTimeSolved.substring(8,10)),(obj[x].DateTimeSolved.substring(11,13)),(obj[x].DateTimeSolved.substring(14,16)),(obj[x].DateTimeSolved.substring(17,19)));
                                difference=(endDate-startDate)/1000;
                                informationH[x]=difference;
                            }
                        }
                    })
                    var ctx2 = document.getElementById("hardwareChart").getContext('2d');
                    var chart2 = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: hardware,
                            datasets: [{
                                label: "Hardware Time to Solve (seconds)",
                                backgroundColor: 'rgb(0,0,0)',
                                borderCoolor: 'rgb(0,0,0)',
                                data: informationH,
                            }]
                        },
                        options: {
                            scales:{
                                yAxes:[{
                                    ticks:{
                                        suggestedMin: 0,
                                    }
                                }]
                            }
                        }
                    });

                }(jQuery))
            }

            var specialists = [];
            var problemSolved = [];           
            function useSpecTable() {
                $(document).ready(function() {
                    $.get("getSpecTable.php", function(data) {
                        specialists = [];
                        problemSolved = [];
                        var obj = JSON.parse(data);  
                        var length = obj.length;
                        for(var z=0;z<length;z++){
                            specialists.push(obj[z].Name);
                            problemSolved.push(obj[z].ProblemCount);
                        }                   
                    })

                    var ctx3 = document.getElementById("specialistsChart").getContext('2d');
                    var chart3 = new Chart(ctx3, {
                        type: 'bar',
                        data: {
                            labels: specialists,
                            datasets: [{
                                label: "Problems solved per specialist",
                                backgroundColor: 'rgb(0,0,0)',
                                borderCoolor: 'rgb(0,0,0)',
                                data: problemSolved,
                            }]
                        },
                        options: {
                            scales:{
                                yAxes:[{
                                    ticks:{
                                        suggestedMin: 0,
                                    }
                                }]
                            }
                        }
                    });

                }(jQuery))
            }

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
			
			//Switches to the subTab to view the extra details of a problem
            function viewProblem(ProbID){
                openTab(event, 'viewProblem', 'sub');
				$(document).ready(function() {
                    $.get("getProblem.php", {"ProbID": ProbID}, function(data) {
                        document.getElementById(type+"Div").innerHTML = data;
                    })
                }(jQuery))
            }

            //redirects the user to the login page when the logout option is selected in the user/profile drop down menu
            function logout() {
                window.location.assign("login.php");
            }

            //will, when completed, take the user to/allow them to edit username/password, will utilise the username input when logging in to determine which username/password to change
            function profile() {
                document.getElementById("profileMenu").classList.toggle("visible");                     
            }

            //causes the, initially hidden, drop down menu to appear when the user icon is clicked, hides the menu when the icon is clicked again
            function dropDownMenu() {
                document.getElementById("userDropDownMenu").classList.toggle("visible");
            }

            function hideOption(){
                if (document.getElementById("hardwaresoftware").selectedIndex == 1) {
                    document.getElementById("softwareLicence").style.display = "unset";
                    document.getElementById("softwareLicenceLabel").style.display = "unset";
                    document.getElementById("softwareSupport").style.display = "unset";
                    document.getElementById("softwareSupportLabel").style.display = "unset";
                    document.getElementById("softwareLabel").style.display = "unset";
                    document.getElementById("software").style.display = "unset";
                    document.getElementById("softwareIdLabel").style.display = "unset";
                    document.getElementById("softwareId").style.display = "unset";
                    document.getElementById("hardware").style.display = "none";
                    document.getElementById("hardwareLabel").style.display = "none";
                    document.getElementById("hardwareSerial").style.display = "none";
                    document.getElementById("hardwareSerialLabel").style.display = "none";
                }
                if (document.getElementById("hardwaresoftware").selectedIndex == 0) {
                    document.getElementById("hardwareSerial").style.display = "unset";
                    document.getElementById("hardwareSerialLabel").style.display = "unset";
                    document.getElementById("hardwareLabel").style.display = "unset";
                    document.getElementById("hardware").style.display = "unset";
                    document.getElementById("software").style.display = "none";
                    document.getElementById("softwareLabel").style.display = "none";
                    document.getElementById("softwareId").style.display = "none";
                    document.getElementById("softwareIdLabel").style.display = "none";
                    document.getElementById("softwareLicence").style.display = "none";
                    document.getElementById("softwareLicenceLabel").style.display = "none";
                    document.getElementById("softwareSupport").style.display = "none";
                    document.getElementById("softwareSupportLabel").style.display = "none";
                }
            }

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
            div.subMenu span.active {
                background-color: #ccc;
            }
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
            .userProfile {
                position: absolute;
                display: none;
                left: 41%;
                top: 33%;
                border: 1px solid black;
                height: 300px;
                width: 350px;
            }       
            .visible {
                display: block;
            }
            .loggedUser {
                text-align: center;
            }
        </style>
    </head>
    <body>

        <!-- div which contains elements of the header bar including the 'title', user icon as well as the drop down menu form the user icon -->
        <div id="headerBar">
            <div id="headerBarLabel">T11 Helpdesk</div>

            <!-- holds the drop down menu items including, initally hidden, options as well as the icon to 'activate' the menu -->
            <div id="userDropDownHolder">
                <img id="userIcon" src="userIcon.png" onclick="dropDownMenu()">

                <!-- initially invisible div which holds the options for the user menu, when the icon is clicked the div is given a visible display attribute -->
                <div id="userDropDownMenu" class="userDropDownContent">

                    <!-- displays the user menu options, profile and logout, as well as the currently logged in user -->
                    <?php echo '<div class="loggedUser">User: '.$user.'</div>'; ?>
                    <div class="dropDownOption" onclick="profile()">Profile</div>
                    <div class="dropDownOption" onclick="logout()">Logout</div>
                </div>
            </div>
        </div>
        <div class='container'>

            <!-- a form contained in an, initially hidden, div which serves as the profile menu, allowing for password changes for a user -->
            <div id="profileMenu" class="userProfile">
                <form method="post" action="passwordChange.php" id="passwordChangeForm">
                    <?php echo "<input type='text' name='currentUser' id='currentUser' value='".$user."' readonly>"; ?>
                    <label id="passworChangeTitle">Change Password:</label>
                    <label id="oldPasswordLabel" for="oldPassword">Old Password:</label>
                    <input type="password" name="oldPassword" id="oldPasswordBox">
                    <label id="newPasswordLabel" for="newPassword">New Password:</label>
                    <input type="password" name="newPassword" id="newPasswordBox">
                    <input type="submit" id="confirmChange" value="Confirm">
                    <input type="button" id="closeProfile" onclick="profile()" value="Close">
                </form> 
            </div>

            <!-- checks, when a password change attempt is made, if the password was changed, whether old password was correct, and alerts whether a change was made -->
            <?php if($passwordChangeAttempt == true) {
    if($passwordChanged == true) echo "<script type='text/javascript'>alert('Password changed successfully!');</script>"; 
    else if($passwordChanged == false) echo "<script type='text/javascript'>alert('Password change failed, please try again.');</script>"; 
}
            ?>				

            <div class="mainMenu">
                <div id="mainImage"><img src="logo.png" alt="Make It All" style="padding:10px; max-width:88%; max-height:88%;"></div>
                <span class="mainTab active" onclick="openTab(event, 'home', 'main')">Home</span>
                <span class="mainTab" onclick="openTab(event, 'newProblem', 'main');openTab(null, 'requiredInfo', 'sub');">New Problem</span>
                <span class="mainTab" onclick="openTab(event, 'problemsList', 'main');getTable('problem');openTab(null, 'problemsTable','sub');">Problems List</span>
                <span class="mainTab" onclick="openTab(event, 'specialistsList', 'main');getTable('specialist');">Specialists List</span>
                <span class="mainTab" onclick="openTab(event, 'hardSoftWareList', 'main');getTable('hardware');getTable('software');">Hardware/Software List</span>
                <span class="mainTab" onclick="openTab(event, 'analytics', 'main');openTab(null, 'analyticsSoftware', 'sub');">Analytics</span>
            </div>
            <div id="home" class="tabContent">
                <h3>Home</h3>
                Welcome to The Helpdesk.
            </div>
            <div id="newProblem" class="tabContent">
                <div class="subMenu">
                    <span class="subTab active" onclick="openTab(event, 'requiredInfo', 'sub')">Required Information</span>
                    <span class="subTab" onclick="openTab(event, 'additionalInfo', 'sub')">Additional Information</span>
                    <span class="subTab" onclick="openTab(event, 'descriptionLog', 'sub')">Problem Description / Log</span>
                </div>
                <form id='newProblemForm' method='post' action='newProblem.php'>
                    <div id="requiredInfo" class="subTabContent">

                        <div class="form-group">
                            <label for="caller">Caller Name*</label>
                            <input type="text" class="form-control" name="caller" placeholder="Joe Bloggs" required><br>
                            <label for="operator">Helpdesk Operator Name*</label>
                            <input type="text" class="form-control" name="operator" placeholder="Alice Smith" required><br>
                            <label for="time">Call Time/Date*</label>
                            <input type="text" class="form-control" name="time" placeholder="06/11/2017 18:50" required><br>
                            <label for="reason">Reason for Call*</label>
                            <input type="text" class="form-control" name="reason" placeholder="Software keeps crashing" required><br>
                            <label for="hardwaresoftware">Hardware/Software*</label>
                            <select class="form-control" name="hardwaresoftware" placeholder="Hardware or Software problem" onchange="hideOption()" onload="hideOption()" required>
                                <option>Hardware</option>
                                <option>Software</option>
                            </select><br>
                            <label for="hardwareID" id="hardwareLabel">Hardware ID</label>
                            <input type="text" class="form-control" name="hardwareID" placeholder="Kodak Printer"><br>
                            <label for="softwareId" id="softwareLabel">Software ID</label>
                            <input type="text" class="form-control" name="softwareId" placeholder="Photoshop"><br>
                            <label for="os">Operating System</label>
                            <input type="text" class="form-control" name="os" placeholder="Windows/Mac OS/Linux"><br>
                            <label for="type">Problem Type</label>
                            <input type="text" class="form-control" name="type" placeholder="Please select most appropriate"><br>
                            <div class="spacer"></div>
                            <!--<input type="submit" id="firstPageSubmit" class="divButtons" />-->
                        </div>
                        <input type='button' value='Next' onclick="openTab(event, 'additionalInfo', 'sub');">
                    </div>
                    <div id="additionalInfo" class="subTabContent">
                        <div class="form-group">
                            <label for="callerID">Caller ID</label>
                            <input type="text" class="form-control" name="callerID" placeholder="Joe Bloggs" ><br>
                            <label for="operatorID">Helpdesk Operator ID</label>
                            <input type="text" class="form-control" name="operatorID" placeholder="Alice Smith"><br>
                            <label for="hardware" id="hardwareIDLabel">Affected Hardware</label>
                            <input type="text" class="form-control" name="hardware" placeholder="C-40392-B"><br>
                            <label for="software" id="softwareIdLabel">Affected Software</label>
                            <input type="text" class="form-control" name="software" placeholder="S1039"><br>
                            <label for="specialistId" id="specialistIdLabel">Assigned Specialist ID</label>
                            <input type="text" class="form-control" name="specialistId" placeholder="S10987"><br>
                            <div class="spacer"></div>
                        </div>
                        <input type='button' value='Next' onclick="openTab(event, 'descriptionLog', 'sub');">
                    </div>
                    <div id="descriptionLog" class="subTabContent">
                        <div class="form-group">
                            <label for="problemDesc" id="problemDescLabel">Problem Description</label>
                            <textarea rows="5" cols="70" class="form-control" name="problemDesc" placeholder="Please enter a description of the problem"></textarea>
                            <div class="spacer"></div>
                        </div>
                        <input type='submit' value='Submit' onclick="alert('do the database stuff');">
                    </div>
                </form>




            </div>
            <div id="problemsList" class="tabContent">
                <div id='problemsTable' class='subTabContent'>
                    <h3>Problems List</h3>
                    <p id="problemDiv"></p>
                </div>
                <div id='viewProblem' class='subTabContent'>
                    View Problem
                </div>
            </div>
            <div id="specialistsList" class="tabContent">
                <h3>Specialists List</h3>
                <p id="specialistDiv"></p>
            </div>
            <div id="hardSoftWareList" class="tabContent">
                <h3>Hardware List</h3>
                <p id="hardwareDiv"></p>
                <h3>Software List</h3>
                <p id="softwareDiv"></p>
            </div>
            <div id="analytics" class="tabContent">
                <div class="subMenu">
                    <span class="subTab active" onclick="openTab(event, 'analyticsSoftware', 'sub');useSoftTable();">Software Analytics</span>
                    <span class="subTab" onclick="openTab(event, 'analyticsHardware', 'sub'); useHardTable();">Hardware Analytics</span>
                    <span class="subTab" onclick="openTab(event, 'analyticsSpecialists', 'sub');useSpecTable();">Specialists Analytics</span>
                </div>
                <h3>Analytics</h3>
                <div id="analyticsSoftware" class="subTabContent">
                    Software Analytics
                    <div class='canvasHolder' style='width:80%'>
                        <canvas id="softwareChart" width="400px", height="150px"></canvas>
                    </div>
                </div>
                <div id="analyticsHardware" class="subTabContent">
                    Hardware Analytics
                    <div class='canvasHolder' style='width:80%'>
                        <canvas id="hardwareChart" width="400px", height="150px"></canvas>
                    </div>
                </div>
                <div id="analyticsSpecialists" class="subTabContent">
                    Specialists Analytics
                    <div class='canvasHolder' style='width:80%'>
                        <canvas id="specialistsChart" width="400px", height="150px"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <script>openTab(event, 'home', 'main');</script>
    </body>
</html>
