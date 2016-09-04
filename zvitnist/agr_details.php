<?php 
require_once('../include/startsession.php');
if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Будь-ласка, <a href="../admin/login.php">увійдіть</a> , для використання даної сторінки.</p>';
    exit();
  }
if ($_SESSION['role_id']==3) {
    echo '<p class="login">Ви не маєте прав для використання даної сторінки.</p></br>
	<a href="../admin/index.php">На головну сторінку.</a>';
    exit();
  } 
$title='Інформація щодо договору';
	  include ('../include/header.php');

if (isset($_GET['rnz'])){
	$rnz = $_GET['rnz'];}
	else echo 'Помилка при виклику інформації';
 
echo '<h2>Інформація щодо договору № ' .$rnz.'</h2>';	  

require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
mysqli_query($dbc, 'SET NAMES UTF8');	 
$query1 = "SELECT * FROM r_reestr1 WHERE rnz='$rnz'";
    $result1 = mysqli_query($dbc, $query1)
      or die('Error querying reestr1.');
	$agr=array();  
	while ($row = mysqli_fetch_array($result1)){
		array_push($agr,$row);}
		
$query2 = "SELECT * FROM r_reestr2 WHERE rnz='$rnz'";
    $result2 = mysqli_query($dbc, $query2)
      or die('Error querying reestr2.'); 
	while ($row = mysqli_fetch_array($result2)){
		array_push($agr,$row);}		

$query3 = "SELECT * FROM vid_rabot INNER JOIN r_reestr1 ON vid_rabot.vid_rabot_kratko=r_reestr1.vid_rabot WHERE rnz='$rnz'";
    $result3 = mysqli_query($dbc, $query3)
      or die('Error querying vid_rabot.'); 
	while ($row = mysqli_fetch_array($result3)){
		array_push($agr,$row);}		

$query4 = "SELECT fio_kratko as fio_manager FROM manager INNER JOIN r_reestr1 ON manager.pp=r_reestr1.manager WHERE rnz='$rnz'";
    $result4 = mysqli_query($dbc, $query4)
      or die('Error querying manager1.'); 
	while ($row = mysqli_fetch_array($result4)){
		array_push($agr,$row);}

$query5 = "SELECT fio_kratko as fio_msdelki FROM manager INNER JOIN r_reestr1 ON manager.pp=r_reestr1.manager_sdelki WHERE rnz='$rnz'";
    $result5 = mysqli_query($dbc, $query5)
      or die('Error querying manager2.'); 
	while ($row = mysqli_fetch_array($result5)){
		array_push($agr,$row);}

$table = "<table>";
$table .= "<tr><td>Номер договору</td><td>".$agr[0]['rnz']."</td></tr>";
$table .= "<tr><td>Дата договору</td><td>".$agr[0]['data_reestr']."</td></tr>";
$table .= "<tr><td>Вид робіт коротко</td><td>".$agr[0]['vid_rabot']."</td></tr>";
$table .= "<tr><td>Вид робіт повністю</td><td>".$agr[2]['vid_rabot_full']."</td></tr>";
$table .= "<tr><td>Тип робіт</td><td>".$agr[2]['tip_rabot']."</td></tr>";
$table .= "<tr><td>Замовник</td><td>".$agr[0]['zakazchik']."</td></tr>";
$table .= "<tr><td>Предмет договору</td><td>".$agr[0]['zadacha']."</td></tr>";
$table .= "<tr><td>Менеджер</td><td>".$agr[3]['fio_manager']."</td></tr>";
$table .= "<tr><td>Місто робіт</td><td>".$agr[0]['gorod']."</td></tr>";
$table .= "<tr><td>Адреса робіт</td><td>".$agr[0]['adres']."</td></tr>";
$table .= "<tr><td>Сума робіт</td><td>".$agr[0]['summa_rabot']."</td></tr>";
$table .= "<tr><td>Тип джерела інформації</td><td>".$agr[0]['tip_dzerela']."</td></tr>";
$table .= "<tr><td>Джерело інформації</td><td>".$agr[0]['dzerelo']."</td></tr>";
$table .= "<tr><td>Менеджер угоди</td><td>".$agr[4]['fio_msdelki']."</td></tr>";
$table .= "<tr><td>Планові витрати</td><td>".$agr[1]['zatraty_plan']."</td></tr>";
$table .= "<tr><td>Премія</td><td>".$agr[1]['premia']."</td></tr>";
$table .= "<tr><td>Стадія робіт</td><td>".$agr[1]['stadiya']."</td></tr>";
$table .= "<tr><td>Планова дата виконання</td><td>".$agr[1]['data_planovo']."</td></tr>";
$table .= "<tr><td>Фактична дата виконання</td><td>".$agr[1]['data_vykon']."</td></tr>";
$table .= "<tr><td>Податки</td><td>".$agr[1]['nalogi']."</td></tr>";
$table .= "<tr><td>Геодезія</td><td>".$agr[1]['geodezia']."</td></tr>";
$table .= "<tr><td>Агентські</td><td>".$agr[1]['agentskie']."</td></tr>";      
$table .= "<tr><td>Бонус</td><td>".$agr[1]['bonus']."</td></tr>";		
$table .= "<tr><td>План прибутку</td><td>".$agr[1]['pribil_plan']."</td></tr>";	
$table .= "<tr><td>Примітки</td><td>".$agr[0]['etc']."</td></tr>";	  
$table .= "</table> ";
echo $table; 
   
mysqli_close($dbc);
?>
     
	 