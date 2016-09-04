<?php
require_once('../include/dbconnect.php');  		
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
				or die('Error connecting to MySQL server.');
				mysqli_query($dbc, 'SET NAMES UTF8');
$rnz=mysqli_real_escape_string($dbc,$_GET["rnz"]);
$sql = "SELECT summa_rabot FROM r_reestr1 WHERE rnz='$rnz'"; 
$res = mysqli_query($dbc, $sql)  or die(mysql_error());
$response=array();
while ($r = mysqli_fetch_array($res)){ 
	/* $response[""] = "--"; */
    $response[$r['summa_rabot']] = $r['summa_rabot'];
   }   
print json_encode($response);
mysqli_close($dbc);
?>

 