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
$title='Стан виконання робіт';
	  include ('../include/header.php'); ?>
     <!-- Подключаем заголовок и меню-->
    
    <div id="content">
        <h3>Довідники</h3>
		<h2>Стан виконання робіт</h2>
		<h5>Інформація, обов'язкова для заповнення, позначена *</h5>
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
 if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
 //Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
	 $stan_vykon=$etc=null;
	 $output_form = true;}

 else if (isset($_POST['submit'])) //Проверка, была ли уже отправка данных
	{
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
	$stan_vykon = trim($_POST['stan_vykon']); //И данные формы присваиваются соответствующим переменным
	if (empty($stan_vykon)) { //Если переменная пустая, то данные формы не заполнены
      echo "<p class='errortext'>Не заповнено вид стану виконання робіт.</p><br />";
      $output_form = true; //Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }
	$etc = trim($_POST['etc']);	    
  }
  else {
    $output_form = true;
  }
	   
 if (!$output_form){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Error connecting to MySQL server.');
    mysqli_query($dbc, 'SET NAMES UTF8');
	
    $stan_vykon = mysqli_real_escape_string($dbc, $stan_vykon);
	$etc = mysqli_real_escape_string($dbc, $etc);
	
    $query = "INSERT INTO stan_vykon (stan_vykon, etc) " .
    "VALUES ('$stan_vykon', '$etc')";

    $result = mysqli_query($dbc, $query)
      or die('Error querying database.');

	echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';
		  
    $sql = "SELECT * FROM stan_vykon order by pp desc limit 1"; 
     //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql)  or die(mysql_error());
    $table = "<table>";
	   $table .= "<tr>"; //Формирование заголовка таблицы
       $table .= "<th>".'Стан виконання робіт'."</th>";
	   $table .= "<th>".'Примітки'."</th>";
       $table .= "</tr>";
    while ($row = mysqli_fetch_array($result))//Формирование данных таблицы
    {
       $table .= "<tr>"; 
       $table .= "<td>".$row['stan_vykon']."</td>";
	   $table .= "<td>".$row['etc']."</td>";
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
				<span>Стан виконання робіт: *</span>
				<div class="bg"><input type="text" name="stan_vykon" class="input" value="<?php echo $stan_vykon; ?>"/></div>
			</div>
			<div  class="textarea_box">
				<span>Примітки:</span>
				<div class="bg"><textarea name="etc" cols="52" rows="5" value="<?php echo $etc; ?>"></textarea></div>
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
