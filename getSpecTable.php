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
$sql = "SELECT Personnel.Name, COUNT(ProblemInfo.SolvedByID) FROM Personnel JOIN Specialists ON  Personnel.PersonnelID = Specialists.PersonnelID JOIN ProblemInfo ON Specialists.SpecialistID = ProblemInfo.SolvedByID WHERE ProblemInfo.SolvedByID > 0 GROUP BY Personnel.Name";
$result = $conn->query($sql);
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);
$conn->close();
?>
