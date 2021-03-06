<?php
$servername = "localhost";
include "team11-mysql-connect.php"; //to provide $username,$password
$dbname = "team11project";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Software.SoftwareID, Software.SoftwareName, ProblemInfo.CallDateTime, ProblemInfo.DateTimeSolved FROM Software JOIN ProblemInfo ON Software.SoftwareID = ProblemInfo.SoftwareID WHERE ProblemInfo.DateTimeSolved != \"0000-00-00 00:00:00\" AND Software.SoftwareID >0 ORDER BY Software.SoftwareID";

$result = $conn->query($sql);

$rows = [];

while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

echo json_encode($rows);

$conn->close();
?>