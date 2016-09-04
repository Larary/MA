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
$title='Типи робіт';
	  include ('../include/header.php'); ?>
     <!-- Подключаем заголовок и меню-->
    
    <div id="content">
        <h3>Довідники</h3>
		<h2>Типи робіт</h2>
		<h5>Інформація, обов'язкова для заповнення, позначена *</h5>
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
 if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
 //Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
	 $tip_rabot_kratko=$tip_rabot_full=null;
	 $output_form = true;}

 else if (isset($_POST['submit'])) //Проверка, была ли уже отправка данных
	{
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
	$tip_rabot_kratko = trim($_POST['tip_rabot_kratko']); //И данные формы присваиваются соответствующим переменным
	if (empty($tip_rabot_kratko)) { //Если переменная пустая, то данные формы не заполнены
      echo "<p class='errortext'>Не заповнено скорочену назву типу робіт.</p><br />";
      $output_form = true; //Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }
	$tip_rabot_full = trim($_POST['tip_rabot_full']); 
	if (empty($tip_rabot_full)) { 
      echo "<p class='errortext'>Не заповнено повну назву типу робіт.</p><br />";
      $output_form = true; 
    }
		    
  }
  else {
    $output_form = true;
  }
	   
 if (!$output_form){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Error connecting to MySQL server.');
    mysqli_query($dbc, 'SET NAMES UTF8');
	
    $tip_rabot_kratko = mysqli_real_escape_string($dbc, $tip_rabot_kratko);
	$tip_rabot_full = mysqli_real_escape_string($dbc, $tip_rabot_full);
	
    $query = "INSERT INTO tip_rabot (tip_rabot_kratko, tip_rabot_full) " .
    "VALUES ('$tip_rabot_kratko', '$tip_rabot_full')";

    $result = mysqli_query($dbc, $query)
      or die('Error querying database.');

	echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';
		  
    $sql = "SELECT * FROM tip_rabot order by pp desc limit 1"; 
     //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql)  or die(mysql_error());
    $table = "<table>";
	   $table .= "<tr>"; //Формирование заголовка таблицы
       $table .= "<th>".'Тип робіт коротко'."</th>";
	   $table .= "<th>".'Тип робіт повністю'."</th>";
       $table .= "</tr>";
    while ($row = mysqli_fetch_array($result))//Формирование данных таблицы
    {
       $table .= "<tr>"; 
       $table .= "<td>".$row['tip_rabot_kratko']."</td>";
	   $table .= "<td>".$row['tip_rabot_full']."</td>";
       $table .= "</tr>";
     }
       $table .= "</table> ";
      echo $table;
   
    mysqli_close($dbc);
  }

  if ($output_form) { //Если проверочная переменная = true, выводится форма для заполнения
?>

	<form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<div>
			<div  class="wrapper">
				<span>Тип робіт скорочено: *</span>
				<div class="bg"><input type="text" name="tip_rabot_kratko" class="input" value="<?php echo $tip_rabot_kratko; ?>"/></div>
			</div>
			<div  class="wrapper">
				<span>Тип робіт повністю: *</span>
				<div class="bg"><input type="text" name="tip_rabot_full" class="input" value="<?php echo $tip_rabot_full; ?>"/></div>
			</div>
			<!-- <a href="#" class="button" onClick="document.getElementById('Form').submit()">ОК</a>-->
			<input class="button" type="submit" value="OK" name="submit" /> 
		</div>
	</form>

<?php
  }
?>		
		       
    </div>
    <!-- <footer>
      <p>Copyright &copy; </p>
    </footer> -->
	</div>
	<p>&nbsp;</p>
	
	</body>
</html>
