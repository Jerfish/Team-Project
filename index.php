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
		//This function is used to filter/search through my tables. The parameter i is used to identify which table we are searching, and from which input we are pulling our search phrase.
            function filterTable(i) {
		//I declare my variables - input is the html input textbox we are getting the search phrase from. Filter is the search phrase we pull from input.
		//table is the reference to the table we are sorting. tr will reference each row in turn to see if it contains the filter. td will reference each cell on a row to check its contents for filter. 
                var input, filter, table, tr, td, i;
                input = document.getElementById(i+'Search');
                filter = input.value.toUpperCase();
                table = document.getElementById(i+'Table');
                tr = table.getElementsByTagName("tr");
		//I hide every row
                for (x = 1; x < tr.length; x++) {
                    tr[x].style.display = "none";
                }
                var str = i + 'Table';
		//for ech column, we search through every row for the filter text - if it appears, we make the whole row visible.
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

		//softTable is used to sort a table by the clicked header. parameter n is the column number we are sorting with and parameter i identifies which table we are sorting.
            function sortTable(n, i) {
		//table is a reference to the table we are sorting. rows is an array of the rows of the table. switching is a boolean which indicates if we have switched anything this loop.
		//i identifies the table we're using. x and y are the references to adjacent rows. shouldSwitch indicates if a pair of rows should switch.
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

                            if(rows[0].getElementsByTagName("th")[n].innerHTML == "Problem ID"){
                                if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
                                    //if so, mark as a switch and break the loop:
                                    shouldSwitch = true;
                                    break;
                                }
                            }else{
                                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                    //if so, mark as a switch and break the loop:
                                    shouldSwitch = true;
                                    break;
                                }
                            }
                        } else if (dir == "desc") {

                            if(rows[0].getElementsByTagName("th")[n].innerHTML == "Problem ID"){
                                if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
                                    //if so, mark as a switch and break the loop:
                                    shouldSwitch = true;
                                    break;
                                }
                            }else{
                                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                    //if so, mark as a switch and break the loop:
                                    shouldSwitch = true;
                                    break;
                                }
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

            var homeData = [];           

            function useHomeTable() {
                $(document).ready(function() {
                    $.get("getHomeTable.php", function(data) {				
                        var obj = JSON.parse(data);
                        homeData[0] = obj[0].Count;
                        homeData[1] = obj[1].Count;
                        homeData[2]= obj[2].Count;
                    })
                }(jQuery))
            }
            var software = [];
            var information = [];           
	var number = [];

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
                        number=[];
                        for(var x =0;x<length;x++){
                            if(indexes.indexOf(obj[x].SoftwareID)>0){
                                startDate = new Date((obj[x].CallDateTime.substring(0,4)),(obj[x].CallDateTime.substring(5,7)-1),(obj[x].CallDateTime.substring(8,10)),(obj[x].CallDateTime.substring(11,13)),(obj[x].CallDateTime.substring(14,16)),(obj[x].CallDateTime.substring(17,19)));
                                endDate = new Date((obj[x].DateTimeSolved.substring(0,4)),(obj[x].DateTimeSolved.substring(5,7)-1),(obj[x].DateTimeSolved.substring(8,10)),(obj[x].DateTimeSolved.substring(11,13)),(obj[x].DateTimeSolved.substring(14,16)),(obj[x].DateTimeSolved.substring(17,19)));
                                difference=(endDate-startDate)/1000*0.000012;
                                information[x]=((information[x] * number[x])+difference)/(number[x]+1);
                                number[x] = number[x] + 1;
                            }else{
                                indexes.push(obj[x].SoftwareID);
                                software[x] = obj[x].SoftwareName;
                                startDate = new Date((obj[x].CallDateTime.substring(0,4)),(obj[x].CallDateTime.substring(5,7)-1),(obj[x].CallDateTime.substring(8,10)),(obj[x].CallDateTime.substring(11,13)),(obj[x].CallDateTime.substring(14,16)),(obj[x].CallDateTime.substring(17,19)));
                                endDate = new Date((obj[x].DateTimeSolved.substring(0,4)),(obj[x].DateTimeSolved.substring(5,7)-1),(obj[x].DateTimeSolved.substring(8,10)),(obj[x].DateTimeSolved.substring(11,13)),(obj[x].DateTimeSolved.substring(14,16)),(obj[x].DateTimeSolved.substring(17,19)));
                                difference=(endDate-startDate)/1000*0.000012;
                                information[x]=difference;
                                number[x] = 1;
                            }
                        }
                    });
                }(jQuery))
            }

            var hardware = [];
            var informationH = [];
	var numberH = [];
           
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
                        numberH = [];
                        for(var x =0;x<length;x++){
                            if(indexes.indexOf(obj[x].HardwareID)>0){
                                startDate = new Date((obj[x].CallDateTime.substring(0,4)),(obj[x].CallDateTime.substring(5,7)-1),(obj[x].CallDateTime.substring(8,10)),(obj[x].CallDateTime.substring(11,13)),(obj[x].CallDateTime.substring(14,16)),(obj[x].CallDateTime.substring(17,19)));
                                endDate = new Date((obj[x].DateTimeSolved.substring(0,4)),(obj[x].DateTimeSolved.substring(5,7)-1),(obj[x].DateTimeSolved.substring(8,10)),(obj[x].DateTimeSolved.substring(11,13)),(obj[x].DateTimeSolved.substring(14,16)),(obj[x].DateTimeSolved.substring(17,19)));
                                difference=(endDate-startDate)/1000*0.000012;
                                informationH[x]=((informationH[x] * numberH[x])+difference)/(numberH[x]+1);
                                number[x] = number[x] + 1;
                            }else{
                                indexes.push(obj[x].HardwareID);
                                hardware[x] = obj[x].HardwareType;
                                startDate = new Date((obj[x].CallDateTime.substring(0,4)),(obj[x].CallDateTime.substring(5,7)-1),(obj[x].CallDateTime.substring(8,10)),(obj[x].CallDateTime.substring(11,13)),(obj[x].CallDateTime.substring(14,16)),(obj[x].CallDateTime.substring(17,19)));
                                endDate = new Date((obj[x].DateTimeSolved.substring(0,4)),(obj[x].DateTimeSolved.substring(5,7)-1),(obj[x].DateTimeSolved.substring(8,10)),(obj[x].DateTimeSolved.substring(11,13)),(obj[x].DateTimeSolved.substring(14,16)),(obj[x].DateTimeSolved.substring(17,19)));
                                difference=(endDate-startDate)/1000*0.000012;
                                informationH[x]=difference;
                                numberH[x] = 1;
                            }
                        }
                    });
                }(jQuery))
            }

            var specialists = [];
            var problemSolved = [];         
            var problemUnsolved = [];
  
            function useSpecTable() {
                $(document).ready(function() {
                    $.get("getSpecTable.php", function(data) {
                        specialists = [];
                        problemSolved = [];
                        problemUnsolved = [];
                        var obj = JSON.parse(data);  
                        var length = obj.length;
                        for(var z=0;z<length/2;z++){
                            specialists.push(obj[z].Name);
                            problemSolved.push(obj[z].SolvedCount);
                        }
                        for(var x=length/2;x<length;x++){
                            problemUnsolved.push(obj[x].UnsolvedCount);
                        }	                   
                    })

                }(jQuery))
            }

            //Switches the focused section to the correct page when a tab is selected, uses the selected tab as parameter to determine displayed page
            function openTab(event, tabName, type) {
		//gets the tabs and tabContents which we want
                var i, tabContent, mainTab;
                if(type == "main"){
                    tabContent = document.getElementsByClassName("tabContent");
                    mainTab = document.getElementsByClassName("mainTab");
                }else if(type =="sub"){
                    tabContent = document.getElementsByClassName("subTabContent");
                    mainTab = document.getElementsByClassName("subTab");
                }
		//this goes through and makes all the tabs disappear
                for(i = 0; i < tabContent.length; i++){
                    tabContent[i].style.display = "none";
                }
		//this makes all the tabs become unhighlighted
                for(i = 0; i < mainTab.length; i++){
                    mainTab[i].className = mainTab[i].className.replace(" active", "");
                }
		//this makes the tab we clicked highlight, and displays the tab content we want.
                document.getElementById(tabName).style.display = "inline";
                event.currentTarget.className += " active";
            }

            function drawSoftTable(){
                var ctx = document.getElementById("softwareChart").getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: software,
                        datasets: [{
                            label: "Software Time to Solve (days)",
                           yAxisID:'A',
 				backgroundColor: 'rgb(76,0,153)',
                            borderColor: 'rgb(0,0,0)',
                            data: information,
                        },{
			label: "Number of Problems",
			yAxisID: 'B',
			data: number,
			backgroundColor: 'rgb(102,0,102)',
			borderColor: 'rgb(0,0,0)',
}]
                    },
                    options: {
                        scales:{
                            yAxes:[{
				id: 'A',
				position: 'left',
                                ticks:{
                                    suggestedMin: 0,
                                },
				scaleLabel:{
					display: true,
					labelString:"Time",
				}
                            },{
				id: 'B',
				position: 'right',
				ticks:{
					suggestedMin:0,
				},
				scaleLabel:{
					display: true,
					labelString: "Number",
				}
}]
                        }
                    }
                });
            }

            function drawHardTable(){
                var ctx2 = document.getElementById("hardwareChart").getContext('2d');
                var chart2 = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: hardware,
                        datasets: [{
                            label: "Hardware Time to Solve (days)",
                            backgroundColor: 'rgb(204,0,0)',
                            borderColor: 'rgb(0,0,0)',
                            data: informationH,
				yAxisID : 'A',
                        },{
			label: "Number of Problems",
			backgroundColor: 'rgb(255,128,0)',
			borderColor: 'rgb(0,0,0)',
			data: numberH,
			yAxisID : 'B',
			}]
                    },
                    options: {
                        scales:{
                            yAxes:[{
				id:'A',
				position: 'left',
                                ticks:{
                                    suggestedMin: 0,
                                },
				scaleLabel:{
					display: true,
					labelString: "Time",
				}
                            },{
				id:'B',
				position:'right',
				ticks:{
					suggestedMin:0,
				},
				scaleLabel:{
					display: true,
					labelString: "Number",
				}
				}]
                        }
                    }
                });
            }

            function drawSpecTable(){
                var ctx3 = document.getElementById("specialistsChart").getContext('2d');
                var chart3 = new Chart(ctx3, {
                    type:'bar',                        
                    data: {
                        labels: specialists,
                        datasets: [{
                            label: "Solved Problems",
                            backgroundColor: 'rgb(76,153,0)',
                            borderColor: 'rgb(0,0,0)',
                            data: problemSolved,
                        },{
                            label: "Unsolved Problems",
                            backgroundColor: 'rgb(204,0,0)',
                            borderColor:'rgb(0,0,0)',
                            data: problemUnsolved,
                        }]
                    },
                    options: {
                        scales:{
                            xAxes:[{
                                stacked:true,
                            }],
                            yAxes:[{
                                stacked:true,
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                        }
                    }
                });
            }

            function viewProblem(ProbID){
                openTab(event, 'viewProblem', 'sub');
                $(document).ready(function() {
                    $.get("getProblem.php", {"ProbID": ProbID}, function(data) {
                        document.getElementById("viewProblem").innerHTML = data;
                    })
                })
            }
			
			function editProblem(){
				$("fieldset").prop('disabled', false);
				
			}

            //redirects the user to the login page when the logout option is selected in the user/profile drop down menu
            function logout() {
                window.location.assign("login.php");
            }

            //takes the user to/allow them to edit username/password, utilises the username input when logging in to determine which username/password to change
            function profile() {
                document.getElementById("profileMenu").classList.toggle("visible");                     
            }

            //causes the, initially hidden, drop down menu to appear when the user icon is clicked, hides the menu when the icon is clicked again
            function dropDownMenu() {
                document.getElementById("userDropDownMenu").classList.toggle("visible");
            }
		

		//this takes the value of the Hardware/Software dropdown box in the New problem form, and hides the irrelevant information.
            function hideOption(){
                if (document.getElementById("hardwaresoftware").selectedIndex == 1) {
                    document.getElementById("softwareLabel").style.display = "unset";
                    document.getElementById("software").style.display = "unset";
                    document.getElementById("softwareIdLabel").style.display = "unset";
                    document.getElementById("softwareId").style.display = "unset";
                    document.getElementById("hardware").style.display = "none";
                    document.getElementById("hardwareLabel").style.display = "none";
                    document.getElementById("hardwareID").style.display = "none";
                    document.getElementById("hardwareIDLabel").style.display = "none";
                }
                if (document.getElementById("hardwaresoftware").selectedIndex == 0) {
                    document.getElementById("hardwareID").style.display = "unset";
                    document.getElementById("hardwareIDLabel").style.display = "unset";
                    document.getElementById("hardwareLabel").style.display = "unset";
                    document.getElementById("hardware").style.display = "unset";
                    document.getElementById("software").style.display = "none";
                    document.getElementById("softwareLabel").style.display = "none";
                    document.getElementById("softwareId").style.display = "none";
                    document.getElementById("softwareIdLabel").style.display = "none";
                }
            }

        </script>
        <style>
            body {
                width: 100%;
                font-family: sans-serif;
                font-size: 11pt;    
            }
            .container {
                height: 93%;
                min-width: 800px;
            }
            div.subMenu {
                float: left;
                border: 1px solid black;
                border-top: none;
                background-color: #f1f1f1;
                width: 100%;
                height: 10%;
                position: relative;
                margin-bottom: 1%;
                min-width: inherit;
            }
            div.subMenu span {
                display: inline-block;
                background-color: inherit;
                color: black;
                padding: 1.75% 0%;
                width: 32%;
                min-width: inherit;
                height: 36.5%;
                text-align: center;
                cursor: pointer;
                font-size: 130%;
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
                background-color: #F1F1F1;
                width: 15%;
                height: 100%;
            }
            div.mainMenu span {
                display: block;
                background-color: inherit;
                color: black;
                padding: 22px 16px;
                width: auto;
                text-align: left;
                cursor: pointer;
                min-width: inherit;
            }
            div.mainMenu div {
                display: block;
                background-color: inherit;
                color: black;
                padding: 22px 16px;
                width: 80%;
                min-width: inherit;
                text-align: left;
                cursor: pointer;
            }
            div.mainMenu span:hover {
                background-color: #ddd;
            }
            div.mainMenu span.active {
                background-color: #ccc;
            }
            .tabContent {
                float: left;
                padding: 0% 1%;
                border: 1px solid black;
                width: 82.6%;
                height: 88%;
                border-left: none;
                height: 100%;
                display:none;
                overflow-x: hidden;
                overflow-y: scroll;
            }
            #headerBar {
                border: 1px solid black;
                height: 6.5%;
                width: 98.9%;
                background-color: #F1F1F1;
                min-width: inherit;
            }
            #headerBarLabel {
                padding: 13px 1px 13px 1px;
                width: 15.05%;
                height: 80%;
                position: relative;
                font-size: 150%;
                border-right: 1px solid black;
                text-align: center;
                display: inline-block;
                vertical-align: middle;
                min-width: inherit;
            }
            #mainImage {
                cursor: default;
                border-bottom: 1px solid black;
                width: auto;
                height: auto;
                position: relative;
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
                z-index: 1000;
            }
            div.dropDownOption:hover {
                background-color: #F1F1F1;
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
                background-color: #f9f9f9;
                height: 300px;
                width: 350px;
                z-index: 1001;
            }       
            .visible {
                display: block;
            }
            .loggedUser {
                text-align: center;
            }
            .form-control{
                display: inline-block;
                padding: 0.5%;
                font-size: 110%;
                margin-bottom: 1.25%;
                margin-top:0.25%;
                vertical-align: middle;
                width: 100%;
                position: relative;
                overflow-x: hidden;
                overflow-y: scroll;
            }
            .subTabContent{
                margin-top: 0.5%;
                width: 100%;
            }
            .form-label{
                margin-top: 0.5%;
                padding-top: 0.5%;
            }
            .next-button{
                width: 10%;
                height: auto;
                font-size: 120%;
            }
            #profileMenu{
                padding: 1%;
            }
            table{
                display: inline-block;
                width: 100%;
                min-width: inherit;
                border-collapse: collapse;
                text-align: left;
                table-layout: auto;
            }
            th{
                padding: 1%;
                padding-left: 0.5%;
                background-color: #DDD;
                border: 1px solid black;
                border-bottom: 2px solid black;
                width: auto;
                font-size: 110%;
            }
            tr th:last-child{
                width: 0.1%;
                white-space:nowrap;
            }
            tr:hover{
                background-color: #CCC;    
            }
            tr:nth-child(even){
                background-color: #EEE;    
            }
            tr td{
                border: 1px solid #AAA;
                padding: 0.5%;
            }
            tr td:last-child{
                width: 0.1%;
                white-space:nowrap;
            }
            td{
                min-width: 10%;
            }


            
            
        </style>
    </head>
    <body>

        <!-- div which contains elements of the header bar including the 'title', user icon as well as the drop down menu form the user icon -->
        <div id="headerBar">
            <div id="headerBarLabel">T11 Helpdesk</div>
            <?php echo "  Logged in as ".$user; ?>

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
                    <?php echo "<input type='text' name='currentUser' id='currentUser' value='".$user."' style='display: none' readonly>"; ?>
                    <label id="passworChangeTitle"><h3>Change Password:</h3></label>
                    <label id="oldPasswordLabel" for="oldPassword" class="form-label">Old Password:</label>
                    <input type="password" name="oldPassword" id="oldPasswordBox" class="form-control">
                    <label id="newPasswordLabel" for="newPassword" class="form-label">New Password:</label>
                    <input type="password" name="newPassword" id="newPasswordBox" class="form-control">
                    <input type="submit" id="confirmChange" value="Confirm" class="next-button" style="width: 45%; margin-top: 1%;">
                    <input type="button" id="closeProfile" onclick="profile()" value="Close"  class="next-button" style="width: 45%; float: right; margin-top: 1%;">
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
                <span class="mainTab active" onclick="openTab(event, 'home', 'main');">Home</span>
                <span class="mainTab" onclick="openTab(event, 'newProblem', 'main');openTab(null, 'requiredInfo', 'sub');">New Problem</span>
                <span class="mainTab" onclick="openTab(event, 'problemsList', 'main');getTable('problem');openTab(null, 'problemsTable','sub');">Problems List</span>
                <span class="mainTab" onclick="openTab(event, 'specialistsList', 'main');getTable('specialist');">Specialists List</span>
                <span class="mainTab" onclick="openTab(event, 'hardSoftWareList', 'main');getTable('hardware');getTable('software');">Hardware/Software List</span>
                <span class="mainTab" onclick="openTab(event, 'analytics', 'main');openTab(event,'analyticsSoftware','sub');drawSoftTable();">Analytics</span>
            </div>
            <div id="home" class="tabContent" style="font-size: 110%;">
                <h3>Home</h3>
                Welcome to The Helpdesk, <?php echo $user;?>
                <br>
                This is your current problem breakdown:
                <br>
                <div class='canvasHolder' style='width:80%'>
                    <canvas id="homeChart" width="400px", height="150px"></canvas>
                    <script>
                        useHomeTable();
                        var ctx4 = document.getElementById("homeChart").getContext('2d');
                        var chart4 = new Chart(ctx4, {
                            type: 'doughnut',
                            data: {
                                labels: ['pending','solved','unassigned'],
                                datasets: [{
                                    label: "Overview",
                                    backgroundColor: ['rgb(255,0,0)','rgb(102,204,0)','rgb(255,255,51)'],
                                    borderColor: 'rgb(0,0,0)',
                                    data: homeData,
                                }]
                            },
                            options: {
                            }
                        });
                    </script>
                </div>


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
                            <label for="caller" class="form-label"><br>Caller Name*  </label>
                            <input type="text" class="form-control" name="caller" placeholder="Joe Bloggs" required oninvalid="alert('Please fill in the Caller's Name')"><br>
                            <label for="operator" class="form-label">Helpdesk Operator Name*  </label>
                            <input type="text" class="form-control" name="operator" placeholder="Alice Smith" required oninvalid="alert('Please fill in the Operator's Name')"><br>
                            <label for="time" class="form-label">Call Time/Date*  </label>
                            <input type="text" class="form-control" name="time" placeholder="2018/02/20 13:25:00" required oninvalid="alert('Please fill in the Call's Date/Time')"><br>
                            <label for="reason" class="form-label">Reason for Call*  </label>
                            <input type="text" class="form-control" name="reason" placeholder="Software keeps crashing" required oninvalid="alert('Please fill in the reason for your call')"><br>
                            <label for="hardwaresoftware" class="form-label">Hardware/Software*  </label>
                            <select class="form-control" name="hardwaresoftware" id="hardwaresoftware" placeholder="Hardware or Software problem" onchange="hideOption()" onload="hideOption()" required>
                                <option>Hardware</option>
                                <option>Software</option>
                            </select><br>
                            <label for="hardwareID" id="hardwareLabel" class="form-label">Hardware ID*  </label>
                            <input type="text" class="form-control" name="hardwareID" id="hardwareID" placeholder="1368"><br>
                            <label for="softwareId" id="softwareLabel" class="form-label">Software ID*  </label>
                            <input type="text" class="form-control" name="softwareId" id="softwareId"placeholder="743"><br>
                            <label for="os" class="form-label">Operating System*  </label>
                            <input type="text" class="form-control" name="os" placeholder="Windows/Mac OS/Linux"required oninvalid="alert('Please fill in the Operating System')"><br>
                            <label for="type" class="form-label">Problem Type*  </label>
                            <input type="text" class="form-control" name="type" placeholder="Please input the Problem Type" required oninvalid="alert('Please fill in the Problem Type')"><br>
                            <div class="spacer"></div>
                            <?php echo "<input type='text' name='currentUserNewProb' id='currentUserNewProb' value='".$user."' style='display: none;' readonly>"; ?>
                        </div>
                        <input type='button' value='Next' class="next-button" onclick="openTab(event, 'additionalInfo', 'sub');">
                    </div>
                    <div id="additionalInfo" class="subTabContent">
                        <div class="form-group">
                            <label for="callerID">Caller ID  </label>
                            <input type="text" class="form-control" name="callerID" placeholder="Joe Bloggs" ><br>
                            <label for="operatorID">Helpdesk Operator ID  </label>
                            <input type="text" class="form-control" name="operatorID" placeholder="Alice Smith"><br>
                            <label for="hardware" id="hardwareIDLabel">Affected Hardware  </label>
                            <input type="text" class="form-control" name="hardware" id="hardware" placeholder="Printer"><br>
                            <label for="software" id="softwareIdLabel">Affected Software  </label>
                            <input type="text" class="form-control" name="software" id="software"placeholder="WinSCP"><br>
                            <label for="specialistId" id="specialistIdLabel">Assigned Specialist ID  </label>
                            <input type="text" class="form-control" name="specialistId" placeholder="S10987"><br>
                            <div class="spacer"></div>
                        </div>
                        <input type='button' value='Next' class="next-button" onclick="openTab(event, 'descriptionLog', 'sub');">
                    </div>
                    <div id="descriptionLog" class="subTabContent">
                        <div class="form-group">

                            <label for="problemDesc" id="problemDescLabel">Problem Description</label>
                            <br>
                            <textarea rows="5" cols="70" class="form-control" name="problemDesc" placeholder="Please enter a description of the problem"></textarea>
                            <div class="spacer"></div>
                        </div>
                        <input type='submit' value='Submit' class="next-button">
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
                    <span class="subTab active" onclick="openTab(event, 'analyticsSoftware', 'sub');drawSoftTable();">Software Analytics</span>
                    <span class="subTab" onclick="openTab(event, 'analyticsHardware', 'sub');drawHardTable();">Hardware Analytics</span>
                    <span class="subTab" onclick="openTab(event, 'analyticsSpecialists', 'sub');drawSpecTable();">Specialists Analytics</span>
                </div>
                <h3>Analytics</h3>
                <div id="analyticsSoftware" class="subTabContent">
                    Software Analytics
                    <div class='canvasHolder1' style='width:80%'>
                        <canvas id="softwareChart" width="400px", height="150px" onclick='drawSoftTable();'></canvas>
                        <script>
                            useSoftTable();
                        </script>
                    </div>
                </div>
                <div id="analyticsHardware" class="subTabContent">
                    Hardware Analytics
                    <div class='canvasHolder2' style='width:80%'>
                        <canvas id="hardwareChart" width="400px", height="150px"></canvas>
                        <script>
                            useHardTable();
                        </script>
                    </div>
                </div>
                <div id="analyticsSpecialists" class="subTabContent">
                    Specialists Analytics
                    <div class='canvasHolder3' style='width:80%'>
                        <canvas id="specialistsChart" width="400px", height="150px"></canvas>
                        <script>
                            useSpecTable();
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <script>openTab(event, 'home', 'main');</script>
    </body>
</html>
