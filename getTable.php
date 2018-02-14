<?php
$type = $_REQUEST['type'];

$servername = "localhost";
include "team11-mysql-connect.php"; //to provide $username,$password
$dbname = "team11project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
if($type == "problem"){
    $sql = "SELECT * FROM ProblemInfo";
}else if($type == "specialist"){
    $sql = "SELECT Specialists.SpecialistID, Specialists.Specialism, Personnel.Name, Personnel.TelNo, COUNT( e.ProblemID ) AS  'Current Problems', COUNT( f.ProblemID ) AS  'Completed Problems', Specialists.PersonnelID
FROM Specialists
INNER JOIN Personnel ON Specialists.PersonnelID = Personnel.PersonnelID
INNER JOIN ProblemInfo e ON e.SpecialistID = Specialists.SpecialistID
INNER JOIN ProblemInfo f ON f.SpecialistID = Specialists.SpecialistID
WHERE e.ProblemID = any(

SELECT ProblemID
FROM ProblemInfo
WHERE STATUS =  'Pending'
)
AND f.ProblemID = any(

SELECT ProblemID
FROM ProblemInfo
WHERE (
CallDateTime
BETWEEN SUBDATE( NOW( ) , INTERVAL 1 
MONTH ) 
AND NOW( )
)
AND 
STATUS =  'Solved'
)
GROUP BY Specialists.SpecialistID";
}else{
    echo "this will work later";
}
$result = $conn->query($sql);
//$value = json_encode($result->fetch_all());

if ($result->num_rows > 0) {
    // output data of each row

    $str = '<input type="text" id="'.$type.'Search" onkeyup="filterTable('.$type.')" placeholder="Search..." style="width: 20%;">';

    if($type == "problem"){
        $str .= "<table id='problemTable'><thead><tr>";
        $str .= "<th onclick='sortTable(0, \"problemTable\")'>Problem ID</th>";
        $str .= "<th onclick='sortTable(1, \"problemTable\")'>Date/Time Opened</th>";
        $str .= "<th onclick='sortTable(2, \"problemTable\")'>ID of Caller</th>";
        $str .= "<th onclick='sortTable(3, \"problemTable\")'>ID of Operator</th>";
        $str .= "<th onclick='sortTable(4, \"problemTable\")'>Hardware/Software</th>";
        $str .= "<th onclick='sortTable(5, \"problemTable\")'>Problem Type</th>";
        $str .= "<th onclick='sortTable(6, \"problemTable\")'>Specialist ID</th>";
        $str .= "<th onclick='sortTable(7, \"problemTable\")'>Date/Time Solved</th>";
        $str .= "<th onclick='sortTable(8, \"problemTable\")'>Status</th>";
        $str .= "</tr></thead><tbody>";
        while($row = $result->fetch_assoc()) {
            $str .= "<tr onclick='viewProblem(".$row["ProblemID"].")'><td>".$row["ProblemID"]."</td><td>".$row["CallDateTime"]."</td><td>".$row["CallerID"]."</td><td>".$row["OperatorID"]."</td><td>";

            if($row["Hardware ID"] != 0){
                $str .= "Hardware";
            }else if($row["Software ID"] != 0){
                $str .= "Software";
            }else{
                $str .= "Error";
            }

            $str .= "</td><td>".$row["ProblemType"]."</td><td>".$row["SpecialistID"]."</td><td>".$row["DateTime Solved"]."</td><td>".$row["Status"]."</td></tr>";
        }
        $str .= '</tbody></table>';
        echo $str;
    } else if($type == "specialist"){
        $str .= "<table id='specialistTable'><thead><tr>";
        $str .= "<th onclick='sortTable(0, \"specialistTable\")'>Specialist ID</th>";
        $str .= "<th onclick='sortTable(1, \"specialistTable\")'>Name</th>";
        $str .= "<th onclick='sortTable(2, \"specialistTable\")'>Specialism</th>";
        $str .= "<th onclick='sortTable(3, \"specialistTable\")'>Tel. No.</th>";
        $str .= "<th onclick='sortTable(4, \"specialistTable\")'>No. Current Problems</th>";
        $str .= "<th onclick='sortTable(5, \"specialistTable\")'>No. Completed Problems </br> (over last month)</th>";
        $str .= "<th onclick='sortTable(6, \"specialistTable\")'>Personnel ID</th>";
        $str .= "</tr></thead><tbody>";
        while($row = $result->fetch_assoc()) {
            $str .= "<tr><td>".$row["SpecialistID"]."</td><td>".$row["Name"]."</td><td>".$row["Specialism"]."</td><td>".$row["TelNo"]."</td><td>"."</td><td>".$row["Current Problems"]."</td><td>".$row["Completed Problems"]."</td><td>".$row["PersonnelID"]."</td></tr>";
        }
        $str .= '</tbody></table>';
        echo $str;
    }
} else {
    echo "0 results";
}
$conn->close();

?>