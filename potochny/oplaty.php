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
$title='Оплати за договорами';
	  include ('../include/header.php'); ?>
     <!-- Подключаем заголовок и меню-->
    
    <div id="content">
        <h3>Поточна діяльність</h3>
		<h2>Оплати за договорами</h2>
		<h5>Інформація, обов'язкова для заповнення, позначена *</h5>
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
 if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
 //Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
	 $rnz=$oplata_fakt=$sposob_oplaty=$etc=null;
	 $output_form = true;}

 else if (isset($_POST['submit'])) //Проверка, была ли уже отправка данных
	{
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
	$rnz = trim($_POST['rnz']); //И данные формы присваиваются соответствующим переменным
	if (empty($rnz)) { //Если переменная пустая, то данные формы не заполнены
      echo "<p class='errortext'>Не заповнено реєстровий номер договору.</p><br />";
      $output_form = true; //Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }
	$data = $_POST['data'];
	if (empty($data)) {
      echo "<p class='errortext'>Не вибрано дату оплати.</p><br />";
      $output_form = true;
    }
	$oplata_fakt = trim($_POST['oplata_fakt']);
	if (empty($oplata_fakt)) {
      echo "<p class='errortext'>Не заповнено суму оплати.</p><br />";
      $output_form = true;
    }
	$sposob_oplaty = trim($_POST['sposob_oplaty']);
	if (empty($sposob_oplaty)) {
      echo "<p class='errortext'>Не вибрано спосіб оплати.</p><br />";
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
	
	$oplata_fakt = str_replace(',','.',$oplata_fakt);//Если пользователь ввел в цифре запятую, меняем на точку
	$oplata_fakt = floatval($oplata_fakt);//Приводим переменную к типу с плавающей точкой во избежание посторонних символов
    $rnz = mysqli_real_escape_string($dbc, $rnz);
	$data = mysqli_real_escape_string($dbc, $data);
    $sposob_oplaty = mysqli_real_escape_string($dbc, $sposob_oplaty);
	$etc = mysqli_real_escape_string($dbc, $etc);
  
    $query = "INSERT INTO r_oplaty (rnz, data, oplata_fakt, sposob_oplaty, etc) " .
    "VALUES ('$rnz', '$data', '$oplata_fakt', '$sposob_oplaty', '$etc')";

    $result = mysqli_query($dbc, $query)
      or die('Error querying database.');

	echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';
		  
    $sql = "SELECT * FROM r_oplaty order by pp desc limit 1"; 
     //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql)  or die(mysql_error());
    $table = "<table>";
	   $table .= "<tr>"; //Формирование заголовка таблицы
       $table .= "<th>".'Номер договору'."</th>";
       $table .= "<th>".'Дата оплати'."</th>";
       $table .= "<th>".'Сума оплати'."</th>";
       $table .= "<th>".'Спосіб оплати'."</th>";
	   $table .= "<th>".'Примітки'."</th>";
       $table .= "</tr>";
    while ($row = mysqli_fetch_array($result))//Формирование данных таблицы
    {
       $table .= "<tr>"; 
       $table .= "<td>".$row['rnz']."</td>";
       $table .= "<td>".$row['data']."</td>";
       $table .= "<td>".$row['oplata_fakt']."</td>";
       $table .= "<td>".$row['sposob_oplaty']."</td>";
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
				<span>Дата оплати за договором: *</span>
				<div class="bg"><input type="date" name="data" class="input" value="<?php echo $data; ?>" ></div>
			</div>
			<div  class="wrapper">
				<span>Сума оплати: *</span>
				<div class="bg"><input type="text" name="oplata_fakt" class="input" value="<?php echo $oplata_fakt; ?>"></div>
			</div>
			<div  class="wrapper">
				<span>Спосіб оплати: *</span>
				<div class="bg"><select name="sposob_oplaty" class="select">
					<option value="0">Оберіть спосіб оплати</option>
					<?php
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
					or die('Error connecting to MySQL server.');
					mysqli_query($dbc, 'SET NAMES UTF8');
					$choice = mysqli_query($dbc,"SELECT sposob_oplaty FROM sposob_oplaty");
						while($row = mysqli_fetch_array($choice)){
							echo "<option value='".$row['sposob_oplaty']."'>".$row['sposob_oplaty']."</option>";
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
