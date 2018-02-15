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
$sql = "SELECT Hardware.HardwareID, Hardware.HardwareName, ProblemInfo.CallDateTime, ProblemInfo.DateTimeSolved FROM Hardware JOIN ProblemInfo ON Hardware.HardwareID = ProblemInfo.HardwareID WHERE ProblemInfo.DateTimeSolved != \"0000-00-00 00:00:00\" AND Hardware.HardwareID >0 ORDER BY Hardware.HardwareID";
$result = $conn->query($sql);
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);
$conn->close();
?>
