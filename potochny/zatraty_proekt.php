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
$title='Витрати за проектами';
	  include ('../include/header.php'); ?>
     <!-- Подключаем заголовок и меню-->
    
    <div id="content">
        <h3>Поточна діяльність</h3>
		<h2>Витрати за проектами</h2>
		<h5>Інформація, обов'язкова для заповнення, позначена *</h5>
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
 if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
 //Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
	 $rnz=$istochnik_oplat=$statya_zatrat=$suma_zatrat=$manager=$etc=null;
	 $output_form = true;}

 else if (isset($_POST['submit'])) //Проверка, была ли уже отправка данных
	{
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
	$rnz = trim($_POST['rnz']); //И данные формы присваиваются соответствующим переменным
	if (empty($rnz)) { //Если переменная пустая, то данные формы не заполнены
      echo "<p class='errortext'>Не заповнено реєстровий номер договору.</p><br />";
      $output_form = true; //Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }
	if (!empty($_POST['istochnik_oplat'])) {$istochnik_oplat = $_POST['istochnik_oplat'];}
    else {echo "<p class='errortext'>Не вибрано джерело оплати.</p><br />";
      $istochnik_oplat = null;
	  $output_form = true;
    }
	$data = $_POST['data'];
	if (empty($data)) {
      echo "<p class='errortext'>Не вибрано дату.</p><br />";
      $output_form = true;
    }
	$statya_zatrat = trim($_POST['statya_zatrat']);
	if (empty($statya_zatrat)) {
      echo "<p class='errortext'>Не вибрано статтю витрат.</p><br />";
      $output_form = true;
    }
	$suma_zatrat = trim($_POST['suma_zatrat']);
	if (empty($suma_zatrat)) {
      echo "<p class='errortext'>Не заповнено суму витрат.</p><br />";
      $output_form = true;
    }
	$manager = trim($_POST['manager']);
	if (empty($manager)) {
      echo "<p class='errortext'>Не вибрано менеджера.</p><br />";
      $output_form = true;
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
	
	$suma_zatrat = str_replace(',','.',$suma_zatrat);//Если пользователь ввел в цифре запятую, меняем на точку
	$suma_zatrat = floatval($suma_zatrat);//Приводим переменную к типу с плавающей точкой во избежание посторонних символов
    $rnz = mysqli_real_escape_string($dbc, $rnz);
	$istochnik_oplat = mysqli_real_escape_string($dbc, $istochnik_oplat);
	$data = mysqli_real_escape_string($dbc, $data);
    $statya_zatrat = mysqli_real_escape_string($dbc, $statya_zatrat);
	$manager = mysqli_real_escape_string($dbc, $manager);
	$etc = mysqli_real_escape_string($dbc, $etc);
  
    $query = "INSERT INTO r_zatraty_proekt (rnz, istochnik_oplat, data, statya_zatrat, suma_zatrat, manager, etc) " .
    "VALUES ('$rnz', '$istochnik_oplat', '$data', '$statya_zatrat', '$suma_zatrat', '$manager', '$etc')";

    $result = mysqli_query($dbc, $query)
      or die('Error querying database.');

	echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';
		  
    $sql = "SELECT * FROM r_zatraty_proekt order by pp desc limit 1"; 
     //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql)  or die(mysql_error());
    $table = "<table>";
	   $table .= "<tr>"; //Формирование заголовка таблицы
       $table .= "<th>".'Номер договору'."</th>";
       $table .= "<th>".'Джерело оплати'."</th>";
       $table .= "<th>".'Дата оплати витрат'."</th>";
       $table .= "<th>".'Стаття витрат'."</th>";
       $table .= "<th>".'Сума витрат'."</th>";
       $table .= "<th>".'Менеджер'."</th>";
	   $table .= "<th>".'Примітки'."</th>";
       $table .= "</tr>";
    while ($row = mysqli_fetch_array($result))//Формирование данных таблицы
    {
       $table .= "<tr>"; 
       $table .= "<td>".$row['rnz']."</td>";
       $table .= "<td>".$row['istochnik_oplat']."</td>";
       $table .= "<td>".$row['data']."</td>";
       $table .= "<td>".$row['statya_zatrat']."</td>";
       $table .= "<td>".$row['suma_zatrat']."</td>";
       $table .= "<td>".$row['manager']."</td>";
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
				<span>Реєстровий номер договору: *</span>
				<div class="bg"><input type="text" name="rnz" class="input" value="<?php echo $rnz; ?>"/></div>
			</div>
			<div  class="wrapper">
				<span>Джерело оплати: *</span>
				<div class="radio">Банк&nbsp;<input type="radio" name="istochnik_oplat" value="банк" <?php if($istochnik_oplat=="банк"){echo 'checked';}?>>
					&nbsp;&nbsp; Каса&nbsp;<input type="radio" name="istochnik_oplat" value="каса" <?php if($istochnik_oplat=="каса"){echo 'checked';}?>>
				</div>
			</div>
			<div  class="wrapper">
				<span>Дата оплати витрат: *</span>
				<div class="bg"><input type="date" name="data" class="input" value="<?php echo $data; ?>" ></div>
			</div>
			<div  class="wrapper">
				<span>Стаття витрат: *</span>
				<div class="bg"><select name="statya_zatrat" class="select">
					<option value="0">Оберіть статтю витрат</option>
					<?php
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
					or die('Error connecting to MySQL server.');
					mysqli_query($dbc, 'SET NAMES UTF8');
					$choice = mysqli_query($dbc,"SELECT statya_zatrat FROM statya_zatrat");
						while($row = mysqli_fetch_array($choice)){
							echo "<option value='".$row['statya_zatrat']."'>".$row['statya_zatrat']."</option>";
						}
					mysqli_close($dbc);
					?>
				</select>			
				</div>
			</div>
			<div  class="wrapper">
				<span>Сума витрат: *</span>
				<div class="bg"><input type="text" name="suma_zatrat" class="input" value="<?php echo $suma_zatrat; ?>"></div>
			</div>
			<div  class="wrapper">
				<span>Менеджер: *</span>
				<div class="bg"><select name="manager" class="select">
					<option value="0">Оберіть менеджера</option>
					<?php
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
					or die('Error connecting to MySQL server.');
					mysqli_query($dbc, 'SET NAMES UTF8');
					$choice = mysqli_query($dbc,"SELECT fio_kratko FROM manager ORDER BY fio_kratko");
						while($row = mysqli_fetch_array($choice)){
							echo "<option value='".$row['fio_kratko']."'>".$row['fio_kratko']."</option>";
						}
					mysqli_close($dbc);
					?>
				</select>			
				</div>
			</div>
			<div  class="textarea_box">
				<span>Примітки:</span>
				<div class="bg"><textarea name="etc" cols="52" rows="5" value="<?php echo $etc; ?>"></textarea></div>
			</div>
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
