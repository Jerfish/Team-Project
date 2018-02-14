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
      $sql= "SELECT Software.SoftwareID, Software.SoftwareName, ProblemInfo.CallDateTime, ProblemInfo.DateTimeSolved FROM Software JOIN ProblemInfo ON Software.SoftwareID = ProblemInfo.SoftwareID WHERE ProblemInfo.DateTimeSolved != \"0000-00-00 00:00:00\" AND Software.SoftwareID >0 ORDER BY Software.SoftwareID" 
}else{
    echo "this will work later";
}
$result = $conn->query($sql);
//$value = json_encode($result->fetch_all());
$num = mysql_num_rows($result);
for ($i = 0; $i < $num; $i++)
{
    $softwareArray[] = mysql_fetch_assoc($result);
}
        echo $softwareArray[];
    }
} else {
    echo "0 results";
}
$conn->close();
?>
