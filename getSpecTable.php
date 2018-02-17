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
$sql = "SELECT Personnel.Name, COUNT(ProblemInfo.SolvedByID) as SolvedCount FROM Personnel JOIN Specialists ON  Personnel.PersonnelID = Specialists.PersonnelID JOIN ProblemInfo ON Specialists.SpecialistID = ProblemInfo.SolvedByID WHERE ProblemInfo.SolvedByID > 0 GROUP BY Personnel.Name";
$result = $conn->query($sql);
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

$sql2="SELECT Personnel.Name, COUNT(ProblemInfo.SolvedByID) as UnsolvedCount FROM `ProblemInfo` JOIN Specialists ON Specialists.SpecialistID = ProblemInfo.SpecialistID JOIN Personnel ON Personnel.PersonnelID = Specialists.SpecialistID WHERE ProblemInfo.SpecialistID !=ProblemInfo.SolvedByID GROUP BY Personnel.Name";
$result = $conn->query($sql2);
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);
$conn->close();
?>
