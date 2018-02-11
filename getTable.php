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
if($type == "Problem"){
    $sql = "SELECT * FROM ProblemInfo";
}else{
    echo "this will work later";
}
$result = $conn->query($sql);
//$value = json_encode($result->fetch_all());

if ($result->num_rows > 0) {
    // output data of each row
    
    $str = '<input type="text" id="'.$type.'Search" onkeyup="filterTable('.$type.')" placeholder="Search..." style="width: 20%;">';

    if($type == "Problem"){
        $str .= "<table><tr><th>Problem ID</th><th>Date/Time Opened</th><th>ID of Caller</th><th>ID of Operator</th><th>Hardware/Software</th><th>Problem Type</th><th>Specialist ID</th><th>Date/Time Solved</th><th>Status</th></tr>";
        while($row = $result->fetch_assoc()) {
            $str .= "<tr><td>".$row["ProblemID"]."</td><td>".$row["CallDateTime"]."</td><td>".$row["CallerID"]."</td><td>".$row["OperatorID"]."</td><td>";
            
            if($row["HardwareID"] == 0){
                $str .= "Software";
            }else if($row["SoftwareID"] == 0){
                $str .= "Hardware";
            }else{
                $str .= "Error"
            }
                
               $str .= "</td><td>".$row["ProblemType"]."</td><td>".$row["SpecialistID"]."</td><td>".$row["DateTime Solved"]."</td><td>".$row["Status"]."</td></tr>";
        }
        $str .= '</table>';
        echo $str;
    }
} else {
    echo "0 results";
}
$conn->close();

?>