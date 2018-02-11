# Team-Project
### Introduction
Our Team Project is to create a helpdesk system for Make-It-All. 

### Team 11
* **Daniel Finch / @finch96**, Project Manager, Authenitication, Programmer
* **Will Jervis / @Jerfish**, Graphic Designer
* **Alex Beaty**, Editor/Regulator
* **Peter Smith / @BlueGandalf**, Programmer
* **Luke Whitehead / @Greedylax**, Programmer
* **Will Thorpe / @MrRibena**, Programmer and VM Manager

#### index.html
This is our login page - once a suitable username and password is entered, it checks against the database. If correct, it then sends you to home.php.

#### home.php
This is the main helpdesk page - it has many tabs, and different functionality for each.

##### Problems List
This displays a table of all problems. When the tab is pressed, the \<div\>  is displayed, and a jQuery function is called. This function uses getTable.php to get a string containing the html of a table. This table is then 'printed' onto the div, displaying the problems. This works whenever you press the tab, which refreshes the table.
  
  
#### getTable.php
This runs an SQL query through the database, then constructs a table and sends it back.

#### team11-mysql-connect.php
This contains the username and password of our database, and is used by getTable.php, among others. 

#### Base Structure Example
Whilst initially this was planned to be our base draft, the code section was messy, and could be heavily improved. As it stands, I think it would make a better example and would serve as a good plan for the structure. 
Some points of interest:
* Under the New Problem tab, the horizontal menu is operational, though it doesnt change much.
* Under the New Problem tab, the text detailing a button will also serve that button's purpose if clicked, moving to the next tab or 'submitting'.
* Under the Problem List tab, if you click the header 'Problem List', the text 'Hello there' will appear, serving as an example of how the 'view Problem' functionality may work.

