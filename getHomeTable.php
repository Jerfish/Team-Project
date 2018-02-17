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
$sql1 = "SELECT Count(SpecialistID) FROM `ProblemInfo` GROUP BY Status";

$result = $conn->query($sq3);
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);
$conn->close();
?>
