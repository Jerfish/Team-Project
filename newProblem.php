<?php
$callerName = $_GET['caller'];
$callerID = $_GET['callerID'];

$operatorName = $_GET['operator'];
$operatorName = $_GET['operatorID'];

$callDateTime = $_GET['time'];

$details = $_GET['reason'];

$problemType = $_GET['type'];

$problemDesc = $_GET['problemDesc'];

$hardID = $_GET['hardwareID'];

$softID = $_GET['softwareId'];

$operatingSystem = $_GET['os'];

$specialistId = $_GET['specialistId'];

$servername = "localhost";
include "team11-mysql-connect.php"; //to provide $username,$password
$dbname = "team11project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if($callerID == ''){
    $sql = 'SELECT PersonnellID FROM Personnel WHERE Name = '.$callerName.';';
    $result = $conn->query($sql);
    $callerID = $result->fetch_assoc();
}
if($operatorID == ''){
    $sql = 'SELECT PersonnellID FROM Personnel WHERE Name = '.$operatorName.';';
    $result = $conn->query($sql);
    $operatorID = $result->fetch_assoc();
}
$status = '';
if($specialistId != ''){
    $status = 'Pending';
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
NULL ,  '".$operatorID."',  '".$callerID."',  '".$callDateTime."',  '".$details."',  '".$problemType."',  '".$problemDesc."',  '".$hardID."',  '".$softID."',  '".$operatingSystem."',  '".$specialistId."',  '',  '',  '".$Status."'
)";

$result = $conn->query($sql);

$conn->close();

?>










