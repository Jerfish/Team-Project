<?php
$type = $_GET[''];

$servername = "localhost";
include "team11-mysql-connect.php"; //to provide $username,$password
$dbname = "team11project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

    $sql = "INSERT INTO  `team11project`.`ProblemInfo` (
`ProblemID` ,
`OperatorID` ,
`CallerID` ,
`CallDateTime` ,
`Details` ,
`ProblemType` ,
`Description/Log` ,
`HardwareID` ,
`SoftwareID` ,
`OperatingSystem` ,
`SpecialistID` ,
`DateTimeSolved` ,
`SolvedByID` ,
`Status`
)
VALUES (
NULL ,  '2',  '1',  '2018-02-17 08:19:21',  'Bugger me',  'testytest',  '',  '1',  '0',  'assdf',  '',  '',  '',  'Unassigned'
)";

$result = $conn->query($sql);

$conn->close();

?>










