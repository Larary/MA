<?php 
require_once('../include/startsession.php');
if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Будь-ласка, <a href="../admin/login.php">увійдіть</a> , для використання даної сторінки.</p>';
    exit();
  }
$title='Внести зміни в договір';
	  include ('../include/header.php'); ?>
     <!-- Подключаем заголовок и меню-->
   
    <div id="content">
        <h3>Поточна діяльність</h3>
		<h2>Внести зміни в договір</h2>
		
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
if (isset($_POST['submit'])) //Проверка, была ли уже отправка данных
{
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Error connecting to MySQL server.');
	mysqli_query($dbc, 'SET NAMES UTF8');
	$change = false; //Вводим указатель необходимости перерасчета $pribil_plan: пересчитываем, если изменяются числовые значения
	$rnz = trim($_POST['rnz']);
	$summa_rabot = trim($_POST['summa_rabot']);
	$summa_rabot = floatval($summa_rabot);
	//Получаем из базы текущие значения для дальнейших расчетов и для сравнительной таблицы по итогам изменений
	$sql = "SELECT * FROM r_reestr2 WHERE rnz='$rnz'";
		$result = mysqli_query($dbc, $sql)  or die(mysql_error());
		while ($row = mysqli_fetch_array($result))
			{ 
			$zatraty_plan_old = $row['zatraty_plan'];
			$premia_old = $row['premia'];
			$stadiya_old = $row['stadiya'];
			$data_planovo_old = $row['data_planovo'];
			$data_vykon_old = $row['data_vykon'];
			$nalogi_old = $row['nalogi'];
			$geodezia_old = $row['geodezia'];
			$agentskie_old = $row['agentskie'];
			$bonus_old = $row['bonus'];
			$pribil_plan_old = $row['pribil_plan'];
			$etc_old = $row['etc'];
			}
	if (isset($_POST['number'])){ //Проверка, нажимались ли чекбоксы для удаления значений
	$number = $_POST['number']; 
	$pribil = $pribil_plan_old;
		if (is_array($number)) { //Чекбоксы нажимались, получаем значения полей для удаления
			foreach ($number as $value) { //Удаляем значения полей
				$q = mysqli_query($dbc,"UPDATE r_reestr2 SET $value='' WHERE rnz='$rnz'");
				switch ($value){ //Также пересчитываем значение поля pribil_plan
					case 'zatraty_plan':
						$pribil = $pribil + $zatraty_plan_old;
						$q1 = mysqli_query($dbc,"UPDATE r_reestr2 SET pribil_plan='$pribil' WHERE rnz='$rnz'");
						break;
					case 'premia':
						$pribil = $pribil + $premia_old;
						$q2 = mysqli_query($dbc,"UPDATE r_reestr2 SET pribil_plan='$pribil' WHERE rnz='$rnz'");
						break;
					case 'nalogi':
						$pribil = $pribil + $nalogi_old;
						$q3 = mysqli_query($dbc,"UPDATE r_reestr2 SET pribil_plan='$pribil' WHERE rnz='$rnz'");
						break;
					case 'geodezia':
						$pribil = $pribil + $geodezia_old;
						$q4 = mysqli_query($dbc,"UPDATE r_reestr2 SET pribil_plan='$pribil' WHERE rnz='$rnz'");
						break;
					case 'agentskie':
						$pribil = $pribil + $agentskie_old;
						$q5 = mysqli_query($dbc,"UPDATE r_reestr2 SET pribil_plan='$pribil' WHERE rnz='$rnz'");
						break;
					case 'bonus':
						$pribil = $pribil + $bonus_old;
						$q6 = mysqli_query($dbc,"UPDATE r_reestr2 SET pribil_plan='$pribil' WHERE rnz='$rnz'");
						break;
				}
			}
		}
	}
	if (!isset($_POST['number'])) //Если чекбоксы на удаление значений не нажимались
	{	
	if(!empty($_POST['zatraty_plan'])) { //Проверяем, в какие поля формы внесены данные для изменений в базе
		$change = true; //Необходимо буде пересчитать pribil_plan
		$zatraty_plan = trim($_POST['zatraty_plan']); //Избавляемся от лишних симоволов пробелов
		$zatraty_plan = str_replace(',','.',$zatraty_plan); //Меняем в числе запятую на точку
		$zatraty_plan = floatval($zatraty_plan); //Приводим число к типу с плавающей точкой
		$query1 = "UPDATE r_reestr2 SET zatraty_plan='$zatraty_plan' WHERE rnz='$rnz'";
		$result1 = mysqli_query($dbc, $query1) 
		or die('Error querying database.');
		} else $zatraty_plan = $zatraty_plan_old; //Если поле формы было пустым, присваиваем переменной уже имеющееся значение
	if(!empty($_POST['premia'])) {
		$change = true;
		$premia = trim($_POST['premia']);
		$premia = str_replace(',','.',$premia);
		$premia = floatval($premia);
		$query2 = "UPDATE r_reestr2 SET premia='$premia' WHERE rnz='$rnz'";
		$result2 = mysqli_query($dbc, $query2) 
		or die('Error querying database.');
		} else $premia = $premia_old;
	if(!empty($_POST['stadiya'])) {
		$stadiya = trim($_POST['stadiya']);
		$stadiya = mysqli_real_escape_string($dbc, $stadiya);
		$query3 = "UPDATE r_reestr2 SET stadiya='$stadiya' WHERE rnz='$rnz'";
		$result3 = mysqli_query($dbc, $query3) 
		or die('Error querying database.');
		}
	if(!empty($_POST['data_planovo'])) {
		$data_planovo = trim($_POST['data_planovo']);
		$data_planovo = mysqli_real_escape_string($dbc, $data_planovo);
		$query4 = "UPDATE r_reestr2 SET data_planovo='$data_planovo' WHERE rnz='$rnz'";
		$result4 = mysqli_query($dbc, $query4) 
		or die('Error querying database.');
		}
	if(!empty($_POST['data_vykon'])) {
		$data_vykon = trim($_POST['data_vykon']);
		$data_vykon = mysqli_real_escape_string($dbc, $data_vykon);
		$query5 = "UPDATE r_reestr2 SET data_vykon='$data_vykon' WHERE rnz='$rnz'";
		$result5 = mysqli_query($dbc, $query5) 
		or die('Error querying database.');
		}
	if(!empty($_POST['nalogi'])) {
		$change = true;
		$nalogi = trim($_POST['nalogi']);
		$nalogi = str_replace(',','.',$nalogi);
		$nalogi = floatval($nalogi);
		$query6 = "UPDATE r_reestr2 SET nalogi='$nalogi' WHERE rnz='$rnz'";
		$result6 = mysqli_query($dbc, $query6) 
		or die('Error querying database.');
		} else $nalogi = $nalogi_old;
	if(!empty($_POST['geodezia'])) {
		$change = true;
		$geodezia = trim($_POST['geodezia']);
		$geodezia = str_replace(',','.',$geodezia);
		$geodezia = floatval($geodezia);
		$query7 = "UPDATE r_reestr2 SET geodezia='$geodezia' WHERE rnz='$rnz'";
		$result7 = mysqli_query($dbc, $query7) 
		or die('Error querying database.');
		} else $geodezia = $geodezia_old;
	if(!empty($_POST['agentskie'])) {
		$change = true;
		$agentskie = trim($_POST['agentskie']);
		$agentskie = str_replace(',','.',$agentskie);
		$agentskie = floatval($agentskie);
		$query8 = "UPDATE r_reestr2 SET agentskie='$agentskie' WHERE rnz='$rnz'";
		$result8 = mysqli_query($dbc, $query8) 
		or die('Error querying database.');
		} else $agentskie = $agentskie_old;
	if(!empty($_POST['bonus'])) {
		$change = true;
		$bonus = trim($_POST['bonus']);
		$bonus = str_replace(',','.',$bonus);
		$bonus = floatval($bonus);
		$query9 = "UPDATE r_reestr2 SET bonus='$bonus' WHERE rnz='$rnz'";
		$result9 = mysqli_query($dbc, $query9) 
		or die('Error querying database.');
		} else $bonus = $bonus_old;
	if($change){
	$pribil_plan = $summa_rabot-$zatraty_plan-$premia-$nalogi-$geodezia-$agentskie-$bonus;
		$query10 = "UPDATE r_reestr2 SET pribil_plan='$pribil_plan' WHERE rnz='$rnz'";
		$result10 = mysqli_query($dbc, $query10) 
		or die('Error querying database.');
		}
	if(!empty($_POST['etc'])) {
		$etc = trim($_POST['etc']);
		$etc = mysqli_real_escape_string($dbc, $etc);
		$query11 = "UPDATE r_reestr2 SET etc='$etc' WHERE rnz='$rnz'";
		$result11 = mysqli_query($dbc, $query11) 
		or die('Error querying database.');
		}
	}
	echo '<h4>Дані успішно змінені.</h4><br />';
		  
    $sql = "SELECT * FROM r_reestr2 WHERE rnz='$rnz'"; 
     //Вывод подтверждающей таблицы об успешном изменении данных
    $result = mysqli_query($dbc, $sql)  or die(mysql_error());
    $table = "<table>";
		$table .= "<tr><th></th>"; //Формирование заголовка таблицы
		$table .= "<th>".'Номер договору'."</th>";
		$table .= "<th>".'Планові витрати'."</th>";
		$table .= "<th>".'Премія'."</th>";
		$table .= "<th>".'Стадія робіт'."</th>";
		$table .= "<th>".'Планова дата виконання'."</th>";
		$table .= "<th>".'Фактична дата виконання'."</th>";
		$table .= "<th>".'Податки'."</th>";
		$table .= "<th>".'Геодезія'."</th>";
		$table .= "<th>".'Агентські'."</th>";
		$table .= "<th>".'Бонус'."</th>";
		$table .= "<th>".'План прибутку'."</th>";
		$table .= "<th>".'Примітки'."</th>";
		$table .= "</tr>";
		$table .= "<tr><td>Попередні дані</td><td>".$rnz."</td><td>".$zatraty_plan_old."</td><td>".$premia_old."</td><td>".
				$stadiya_old."</td><td>".$data_planovo_old."</td><td>".$data_vykon_old."</td><td>".$nalogi_old."</td><td>".
				$geodezia_old."</td><td>".$agentskie_old."</td><td>".$bonus_old."</td><td>".$pribil_plan_old."</td><td>".
				$etc_old."</td></tr>"; //Формирование строки с предыдущими данными
    while ($row = mysqli_fetch_array($result))//Формирование строки с измененными данными
		{
		$table .= "<tr><td>Змінені дані</td><td>".$row['rnz']."</td><td>".$row['zatraty_plan']."</td><td>".
				$row['premia']."</td><td>".$row['stadiya']."</td><td>".$row['data_planovo']."</td><td>".
				$row['data_vykon']."</td><td>".$row['nalogi']."</td><td>".$row['geodezia']."</td><td>".
				$row['agentskie']."</td><td>".$row['bonus']."</td><td>".$row['pribil_plan']."</td><td>".
				$row['etc']."</td></tr>";
		} 
		$table .= "</table> ";
	echo $table;
   
    mysqli_close($dbc);
}

else //Если форма на изменение данных еще не заполнялась, выводим либо форму для изменений, либо форму выбора договора
{ 
	if (isset($_POST['subm_agr'])&&(!empty($_POST['rnz'])))
	{ // Если в форме выбора договора договор выбран и нажато ОК, выводим форму для изменений
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
				or die('Error connecting to MySQL server.');
				mysqli_query($dbc, 'SET NAMES UTF8');
		$rnz = trim($_POST['rnz']);
		$rnz=mysqli_real_escape_string($dbc,$rnz); //Получаем из r_reestr1 базовую информацию о договоре
		$sql1 = "SELECT summa_rabot, data_reestr, zakazchik FROM r_reestr1 WHERE rnz='$rnz'"; 
		$res = mysqli_query($dbc, $sql1) or die(mysql_error());
		while ($r = mysqli_fetch_array($res)){ 
			echo "<h4>Для внесення змін вибрано договір №".$rnz. ", дата договору ".$r['data_reestr']."<br />".
			"замовник ".$r['zakazchik'].", сума робіт ".$r['summa_rabot']." грн.</h4>";
			$summa_rabot = $r['summa_rabot'];
			}
		$sql2 = "SELECT * FROM r_reestr2 WHERE rnz='$rnz'"; //Получаем из r_reestr2 имеющиеся данные для их изменения
		$result = mysqli_query($dbc, $sql2)  or die(mysql_error());
		while ($row = mysqli_fetch_array($result))
			{ 
			$zatraty_plan = $row['zatraty_plan'];
			$premia = $row['premia'];
			$stadiya = $row['stadiya'];
			$data_planovo = $row['data_planovo'];
			$data_vykon = $row['data_vykon'];
			$nalogi = $row['nalogi'];
			$geodezia = $row['geodezia'];
			$agentskie = $row['agentskie'];
			$bonus = $row['bonus'];
			$pribil_plan = $row['pribil_plan'];
			$etc = $row['etc'];
			}
?>
		<!-- Выводим форму в виде таблицы -->
		<form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		<input type="hidden" name="rnz" value="<?php echo $rnz; ?>">
		<table>
		<tr><th>Назва показника</th><th>Наявні дані</th><th>Нові дані</th><th>Видалити дані</th></tr>	
		<tr><td>Вартість робіт за договором</td><td><?php echo $summa_rabot; ?></td>
			<td><input readonly class="table" type="text" id="summa_rabot" name="summa_rabot" form="Form" value="<?php echo $summa_rabot; ?>" oninput="pribilPlan()"></td>
			<td></td></tr>
		<tr><td>Планова сума витрат</td><td><?php echo $zatraty_plan; ?></td>	
			<td><input class="table" type="text" name="zatraty_plan" id="zatraty_plan" form="Form" oninput="pribilPlan()" ></td>
			<td><input type="checkbox" name="number[]" value="zatraty_plan" form="Form"></td></tr>
		<tr><td>Сума премії</td><td><?php echo $premia; ?></td>	
			<td><input class="table" type="text" name="premia" id="premia" form="Form" oninput="pribilPlan()" ></td>
			<td><input type="checkbox" name="number[]" value="premia" form="Form"></td></tr>
		<tr><td>Стадія робіт за договором</td><td><?php echo $stadiya; ?></td>	
			<td><select name="stadiya" form="Form" class="table-select" >
				<option value="0">Оберіть стадію робіт</option>
					<?php
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
					or die('Error connecting to MySQL server.');
					mysqli_query($dbc, 'SET NAMES UTF8');
					$choice = mysqli_query($dbc,"SELECT * FROM stan_vykon");
						while($row = mysqli_fetch_array($choice)){
							echo "<option value='".$row['stan_vykon']."'>".$row['stan_vykon']."</option>";
						}
					mysqli_close($dbc);
					?>
			</select></td>
			<td><input type="checkbox" name="number[]" value="stadiya" form="Form"></td></tr>
		<tr><td>Планова дата виконання робіт</td><td><?php echo $data_planovo; ?></td>	
			<td><input type="date" name="data_planovo" form="Form" class="table"></td>	
			<td><input type="checkbox" name="number[]" value="data_planovo" form="Form"></td></tr>	
		<tr><td>Фактична дата виконання робіт</td><td><?php echo $data_vykon; ?></td>	
			<td><input type="date" name="data_vykon" form="Form" class="table" ></td>	
			<td><input type="checkbox" name="number[]" value="data_vykon" form="Form"></td></tr>
		<tr><td>Податки</td><td><?php echo $nalogi; ?></td>	
			<td><input class="table" type="text" name="nalogi" id="nalogi" form="Form" oninput="pribilPlan()" ></td>
			<td><input type="checkbox" name="number[]" value="nalogi" form="Form"></td></tr>
		<tr><td>Геодезія</td><td><?php echo $geodezia; ?></td>	
			<td><input class="table" type="text" name="geodezia" id="geodezia" form="Form" oninput="pribilPlan()" ></td>
			<td><input type="checkbox" name="number[]" value="geodezia" form="Form"></td></tr>
		<tr><td>Агентські</td><td><?php echo $agentskie; ?></td>	
			<td><input class="table" type="text" name="agentskie" id="agentskie" form="Form" oninput="pribilPlan()" ></td>
			<td><input type="checkbox" name="number[]" value="agentskie" form="Form"></td></tr>	
		<tr><td>Бонус</td><td><?php echo $bonus; ?></td>	
			<td><input class="table" type="text" name="bonus" id="bonus" form="Form" oninput="pribilPlan()" ></td>
			<td><input type="checkbox" name="number[]" value="bonus" form="Form"></td></tr>		
		<tr><td>План прибутку</td><td><?php echo $pribil_plan; ?></td>	
			<td><output name="pribil_plan" id="pribil_plan" form="Form" ></output></td>
			<td><input type="checkbox" name="number[]" value="pribil_plan" form="Form"></td></tr>	
		<tr><td>Примітки</td><td><?php echo $etc; ?></td>	
			<td><textarea name="etc" cols="10" rows="5" form="Form" class="textarea"></textarea></td>
			<td><input type="checkbox" name="number[]" value="etc" form="Form"></td></tr>	
		</table>
		<input class="button" type="submit" value="OK" name="submit"  onclick='return confirm("Відправити дані?")' />	
		</form>
<?php
	}
	else //Если в форме выбора договора договор еще не выбран и/или ОК не нажато, выводим эту форму
	{
		?>
		<form id="Agreement" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
			<div>
				<div  class="wrapper">
					<span><h4>Оберіть договір: *</h4></span>
					<div class="bg"><select name="rnz" id="rnz" class="select">
						<option value="0">Оберіть договір</option>
							<?php
							$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
							or die('Error connecting to MySQL server.');
							mysqli_query($dbc, 'SET NAMES UTF8');
							$choice = mysqli_query($dbc,"SELECT rnz, data_reestr, zakazchik FROM r_reestr1 ORDER BY pp DESC LIMIT 10");
								while($row = mysqli_fetch_array($choice)){
									echo "<option value='".$row['rnz']."'>".$row['rnz']."&nbsp&nbsp".$row['data_reestr']."&nbsp&nbsp".$row['zakazchik']."</option>";
								}
							mysqli_close($dbc);
							?> 
					</select>			
					</div>	
				</div>	
			<input class="button" type="submit" value="OK" name="subm_agr" />
		</div>
		</form>
<?php
	}
}
?>		
		       
    </div>
    <!-- <footer>
      <p>Copyright &copy; </p>
    </footer> -->
	</div>
	<p>&nbsp;</p>
	
<script type="text/javascript">
function pribilPlan(){
 var summa_rabot = parseFloat(document.getElementById("summa_rabot").value.replace(/,/, '.'))||'<?php echo $summa_rabot; ?>';
 var zatraty_plan = parseFloat(document.getElementById("zatraty_plan").value.replace(/,/, '.'))||'<?php echo $zatraty_plan; ?>';
 var premia = parseFloat(document.getElementById("premia").value.replace(/,/, '.'))||'<?php echo $premia; ?>';
 var nalogi = parseFloat(document.getElementById("nalogi").value.replace(/,/, '.'))||'<?php echo $nalogi; ?>';
 var geodezia = parseFloat(document.getElementById("geodezia").value.replace(/,/, '.'))||'<?php echo $geodezia; ?>';
 var agentskie = parseFloat(document.getElementById("agentskie").value.replace(/,/, '.'))||'<?php echo $agentskie; ?>';
 var bonus = parseFloat(document.getElementById("bonus").value.replace(/,/, '.'))||'<?php echo $bonus; ?>';
 var pribil_plan = summa_rabot-zatraty_plan-premia-nalogi-geodezia-agentskie-bonus;
 document.getElementById('pribil_plan').innerHTML = pribil_plan.toFixed(2);
}
</script>
	</body>
</html>
