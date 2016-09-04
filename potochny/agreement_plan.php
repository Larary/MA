<?php 
require_once('../include/startsession.php');
if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Будь-ласка, <a href="../admin/login.php">увійдіть</a> , для використання даної сторінки.</p>';
    exit();
  }
$title='Планові показники договорів';
	  include ('../include/header.php'); ?>
     <!-- Подключаем заголовок и меню-->
 
    <div id="content">
        <h3>Поточна діяльність</h3>
		<h2>Планові показники договорів</h2>
		<h5>Інформація, обов'язкова для заповнення, позначена *</h5>
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
 if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
 //Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
	 $rnz=$summa_rabot=$zatraty_plan=$data_planovo=$data_vykon=$premia=$dzerelo=$nalogi=$geodezia=$agentskie=$bonus=$pribil_plan=$etc=null;
	 $output_form = true;}

 else if (isset($_POST['submit'])) //Проверка, была ли уже отправка данных
	{
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
	$rnz = trim($_POST['rnz']); //И данные формы присваиваются соответствующим переменным
	if (empty($rnz)) { //Если переменная пустая, то данные формы не заполнены
      echo "<p class='errortext'>Не вибрано договір.</p><br />";
      $output_form = true; //Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }	
	if (isset($_POST['summa_rabot'])){
	$summa_rabot = trim($_POST['summa_rabot']);}
	else {$summa_rabot = 0;}
	$zatraty_plan = trim($_POST['zatraty_plan']);
	$premia = trim($_POST['premia']);
	$stadiya = trim($_POST['stadiya']);
	$data_planovo = trim($_POST['data_planovo']);
	$data_vykon = trim($_POST['data_vykon']);
	$nalogi = trim($_POST['nalogi']);
	$geodezia = trim($_POST['geodezia']);
	$agentskie = trim($_POST['agentskie']);
	$bonus = trim($_POST['bonus']);	
	$etc = trim($_POST['etc']);   
  }
  else {
    $output_form = true;
  }
	
  if (!$output_form){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Error connecting to MySQL server.');
	mysqli_query($dbc, 'SET NAMES UTF8');
	
	$rnz = mysqli_real_escape_string($dbc, $rnz);
    $zatraty_plan = str_replace(',','.',$zatraty_plan);
	$zatraty_plan = floatval($zatraty_plan);
	$premia = str_replace(',','.',$premia);
	$premia = floatval($premia);
	$stadiya = mysqli_real_escape_string($dbc, $stadiya);
	$data_planovo = mysqli_real_escape_string($dbc, $data_planovo);
	$data_vykon = mysqli_real_escape_string($dbc, $data_vykon);
	$nalogi = str_replace(',','.',$nalogi);
	$nalogi = floatval($nalogi);
	$geodezia = str_replace(',','.',$geodezia);
	$geodezia = floatval($geodezia);
	$agentskie = str_replace(',','.',$agentskie);
	$agentskie = floatval($agentskie);
	$bonus = str_replace(',','.',$bonus);
	$bonus = floatval($bonus);
	$pribil_plan = $summa_rabot-$zatraty_plan-$premia-$nalogi-$geodezia-$agentskie-$bonus;
	$etc = mysqli_real_escape_string($dbc, $etc);
  
    $query = "INSERT INTO r_reestr2 (rnz, zatraty_plan, premia, stadiya, data_planovo, data_vykon,
	nalogi, geodezia, agentskie, bonus, pribil_plan, etc) " .
    "VALUES ('$rnz', '$zatraty_plan', '$premia', '$stadiya', '$data_planovo', '$data_vykon', 
	'$nalogi', '$geodezia', '$agentskie', '$bonus', '$pribil_plan', '$etc')";

    $result = mysqli_query($dbc, $query)
      or die('Error querying database.');

	echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';
		  
    $sql = "SELECT * FROM r_reestr2 order by pp desc limit 1"; 
     //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql)  or die(mysql_error());
    $table = "<table>";
	   $table .= "<tr>"; //Формирование заголовка таблицы
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
    while ($row = mysqli_fetch_array($result))//Формирование данных таблицы
    {
       $table .= "<tr>"; 
       $table .= "<td>".$row['rnz']."</td>";
	   $table .= "<td>".$row['zatraty_plan']."</td>";
	   $table .= "<td>".$row['premia']."</td>";
	   $table .= "<td>".$row['stadiya']."</td>";
	   $table .= "<td>".$row['data_planovo']."</td>";
	   $table .= "<td>".$row['data_vykon']."</td>";
	   $table .= "<td>".$row['nalogi']."</td>";
	   $table .= "<td>".$row['geodezia']."</td>";
	   $table .= "<td>".$row['agentskie']."</td>";
	   $table .= "<td>".$row['bonus']."</td>";
	   $table .= "<td>".$row['pribil_plan']."</td>";
	   $table .= "<td>".$row['etc']."</td>";
	   $table .= "</tr>";
     }
       $table .= "</table> ";
      echo $table;
   
    mysqli_close($dbc);
  }

  if ($output_form) { //Если проверочная переменная = true, выводится форма для заполнения
?>

<form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
	<div>
		<div  class="wrapper">
			<span>Реєстровий номер договору: *</span>
			<div class="bg"><select name="rnz" id="rnz" class="select">
				<option value="0">Оберіть договір</option>
					<?php
					$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
					or die('Error connecting to MySQL server.');
					mysqli_query($dbc, 'SET NAMES UTF8');
					$choice = mysqli_query($dbc,"SELECT r_reestr1.rnz, r_reestr1.data_reestr, r_reestr1.zakazchik 
					FROM r_reestr1 LEFT JOIN r_reestr2 USING (rnz) WHERE r_reestr2.rnz IS NULL");
					if (mysqli_num_rows($choice)!=0){
						while($row = mysqli_fetch_array($choice)){
							echo "<option value='".$row['rnz']."'>".$row['rnz']."&nbsp&nbsp".$row['data_reestr']."&nbsp&nbsp".$row['zakazchik']."</option>";
						}}
					else {echo "<option value=''>Відсутні нові договори для внесення даних. 
						Для редагування даних використовуйте форму: ВНЕСТИ ЗМІНИ В ДОГОВІР</option>";}
					
					mysqli_close($dbc);
					?>
			</select>			
			</div>	
		</div>
		<div  class="wrapper">
			<span>Вартість робіт за договором:</span>
			<div class="bg"><select name="summa_rabot" id="summa_rabot" class="select" onchange="pribilPlan()">
			<option value="0">---</option></select>
			</div>
		</div>
		<div  class="wrapper">
			<span>Планова сума витрат:</span>
			<div class="bg"><input type="text" name="zatraty_plan" id="zatraty_plan" class="input" oninput="pribilPlan()" value="<?php echo $zatraty_plan; ?>"></div>
		</div>
		<div  class="wrapper">
			<span>Сума премії:</span>
			<div class="bg"><input type="text" name="premia" id="premia" class="input" oninput="pribilPlan()" value="<?php echo $premia; ?>"></div>
		</div>
		<div  class="wrapper">
			<span>Стадія робіт за договором:</span>
			<div class="bg"><select name="stadiya" class="select">
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
			</select>			
			</div>
		</div>
		<div  class="wrapper">
			<span>Планова дата виконання робіт:</span>
			<div class="bg"><input type="date" name="data_planovo" class="input" value="<?php echo $data_planovo; ?>" ></div>
		</div>
		<div  class="wrapper">
			<span>Фактична дата виконання робіт:</span>
			<div class="bg"><input type="date" name="data_vykon" class="input" value="<?php echo $data_vykon; ?>" ></div>
		</div>
		<div  class="wrapper">
			<span>Податки:</span>
			<div class="bg"><input type="text" name="nalogi" id="nalogi" class="input" oninput="pribilPlan()" value="<?php echo $nalogi; ?>"></div>
		</div>
		<div  class="wrapper">
			<span>Геодезія:</span>
			<div class="bg"><input type="text" name="geodezia" id="geodezia" class="input" oninput="pribilPlan()" value="<?php echo $geodezia; ?>"></div>
		</div>
		<div  class="wrapper">
			<span>Агентські:</span>
			<div class="bg"><input type="text" name="agentskie" id="agentskie" class="input" oninput="pribilPlan()" value="<?php echo $agentskie; ?>"></div>
		</div>
		<div  class="wrapper">
			<span>Бонус:</span>
			<div class="bg"><input type="text" name="bonus" id="bonus" class="input" oninput="pribilPlan()" value="<?php echo $bonus; ?>"></div>
		</div>
		<div  class="wrapper">
			<span>План прибутку:</span>
			<div><output name="pribil_plan" id="pribil_plan" class="output"></output></div>
		</div>
		<div  class="textarea_box">
			<span>Примітки:</span>
			<div class="bg"><textarea name="etc" cols="52" rows="5" value="<?php echo $etc; ?>"></textarea></div>
		</div>
		<input class="button" type="submit" value="OK" name="submit"  onclick='return confirm("Відправити дані?")' /> 
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
	
<script type="text/javascript" charset="utf-8">
$(function() {
$("#summa_rabot").remoteChained({
        parents : "#rnz",
        url : "../include/select_agreement.php",
        loading : "--"
    });
});	
</script>
<script type="text/javascript">
function pribilPlan(){
 var summa_rabot = parseFloat(document.getElementById("summa_rabot").value.replace(/,/, '.'))||0;
 var zatraty_plan = parseFloat(document.getElementById("zatraty_plan").value.replace(/,/, '.'))||0;
 var premia = parseFloat(document.getElementById("premia").value.replace(/,/, '.'))||0;
 var nalogi = parseFloat(document.getElementById("nalogi").value.replace(/,/, '.'))||0;
 var geodezia = parseFloat(document.getElementById("geodezia").value.replace(/,/, '.'))||0;
 var agentskie = parseFloat(document.getElementById("agentskie").value.replace(/,/, '.'))||0;
 var bonus = parseFloat(document.getElementById("bonus").value.replace(/,/, '.'))||0;
 var pribil_plan = summa_rabot-zatraty_plan-premia-nalogi-geodezia-agentskie-bonus;
 document.getElementById('pribil_plan').innerHTML = pribil_plan.toFixed(2);
}
</script>
	</body>
</html>
