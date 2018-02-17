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
$sql1 = "SELECT COUNT(ProblemInfo.Status) as Solved FROM `ProblemInfo` WHERE ProblemInfo.Status = "Solved"";
$sql2 = "SELECT COUNT(ProblemInfo.Status) as Pending FROM `ProblemInfo` WHERE ProblemInfo.Status = "Pending"";
$sql3 = "SELECT COUNT(ProblemInfo.Status) as Unassigned FROM `ProblemInfo` WHERE ProblemInfo.Status = "Unassigned"":
$rows = [];
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
$result = $conn->query($sq2);
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
$result = $conn->query($sq3);
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);
$conn->close();
?>
