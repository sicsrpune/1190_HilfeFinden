<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'r00t123';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
mysql_select_db('android_api');
$email=$_GET["email"];
$interest=$_GET["interest"];
$latitude_user="";
$longitude_user="";

function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}







$sql1="SELECT latitude,longitude from users where email like '$email'";

$retval = mysql_query( $sql1, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{
	$latitude_user=$row['latitude'];
	$longitude_user=$row['longitude'];
} 


$sql = "SELECT name,email,mobile,latitude,longitude from users where email in (SELECT distinct email FROM user_interest where name like '$interest' and email not like '$email')";

$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{
	$row['distance']=number_format(distance($latitude_user, $longitude_user, $row['latitude'], $row['longitude'], "K"),2,'.','')." km";
	 
	$output[]=$row;	
}
 
echo json_encode($output);

mysql_close($conn);


?>
