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
            $str .= "<tr><td>".$row["ProblemID"]."</td><td>".$row["CallDateTime"]."</td><td>".$row["CallerID"]."</td><td>".$row["OperatorID"]."</td><td>";

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
    }
} else {
    echo "0 results";
}
$conn->close();

?>