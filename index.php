<?php 


//  require_once('./vendor/autoload.php');
$con = mysqli_connect("remotemysql.com","hkc9luRsEi","eLn8JCHMpr","hkc9luRsEi","3306");

if(!$con){
  echo mysqli_connect_error();
  die();
}
// echo "Database connected";
// $sql = "CREATE TABLE abcd(
// name VARCHAR(30) NOT NULL PRIMARY KEY,
// phone VARCHAR(30) NOT NULL,
// email VARCHAR(40) NOT NULL,
// gender VARCHAR(10) NOT NULL,
// dob VARCHAR(10) NOT NULL
// )";

// if(mysqli_query($con, $sql)){
//   echo "Table Created Successfully";
// }else{
//   echo "Error creating table" .mysqli_error($con);
// }

$Name = $_POST['fname'];
$Phone = $_POST['phn'];
$Email = $_POST['mail'];
$Gender = $_POST['gender'];
$DOB = $_POST['dateb'];

$sql = "INSERT INTO abcd VALUES ('$Name','$Phone','$Email','$Gender','$DOB')";
$rs = mysqli_query($con, $sql);
if($rs){
  echo "Record Inserted Successfully!";


}else
echo "not inserted";
// mysqli_select_db("pooja");


$que = "SELECT * FROM abcd WHERE name= '$Name'";
$result = mysqli_query($con,$que) or die(mysqli_error($con));
while( $row = mysqli_fetch_array($result)){
  echo "<h3>Your Records are:</h3>";
 $na = $row['name'];
 $ph = $row['phone'];
 $em = $row['email'];
  $gen = $row['gender'];
$db = $row['dob'];
echo " Name: {$na} <br> Contact Number: {$ph} <br> Email ID: {$em} <br> Gender: {$gen} <br> Date Of Birth: {$db}";

}
$que = "SELECT * FROM abcd;
$result = mysqli_query($con,$que) or die(mysqli_error($con));
while( $row = mysqli_fetch_array($result)){
  echo "<h3>Your Records are:</h3>";
 $na = $row['name'];
 $ph = $row['phone'];
 $em = $row['email'];
  $gen = $row['gender'];
$db = $row['dob'];
echo " Name: {$na} <br> Contact Number: {$ph} <br> Email ID: {$em} <br> Gender: {$gen} <br> Date Of Birth: {$db}";

}


?>

