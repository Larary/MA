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
$title='Видані аванси';
include ('../include/header.php'); ?>
<!-- Подключаем заголовок и меню-->
    
<div id="content">
    <h3>Поточна діяльність</h3>
    <h2>Видані аванси</h2>
		
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД

if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
//Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
    $istochnik_oplat=$manager=$suma=$etc=null;
    $output_form = true;
}

else if (isset($_POST['submit'])){ //Проверка, была ли уже отправка данных
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
    $data = $_POST['data'];
    if (empty($data)) {
        echo "<p class='errortext'>Не вибрано дату авансу.</p><br />";
        $output_form = true;//Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }
    if (!empty($_POST['istochnik_oplat'])) {
        $istochnik_oplat = $_POST['istochnik_oplat'];
    }
    else {
        echo "<p class='errortext'>Не вибрано джерело оплати.</p><br />";
	$istochnik_oplat = null;
        $output_form = true;
    }
		
    $manager = trim($_POST['manager']);
    if (empty($manager)) {
        echo "<p class='errortext'>Не вибрано менеджера.</p><br />";
        $output_form = true;
    }
    $suma = trim($_POST['suma']);
    if (empty($suma)) {
        echo "<p class='errortext'>Не заповнено суму авансу.</p><br />";
        $output_form = true;
    }
    $etc = trim($_POST['etc']);
}
else {
    $output_form = true;
}
	
if (!$output_form){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');
    mysqli_query($dbc, 'SET NAMES UTF8');
	
    $suma = str_replace(',','.',$suma);//Если пользователь ввел в цифре запятую, меняем на точку
    $suma = floatval($suma);//Приводим переменную к типу с плавающей точкой во избежание посторонних символов
    $data = mysqli_real_escape_string($dbc, $data);
    $istochnik_oplat = mysqli_real_escape_string($dbc, $istochnik_oplat);
    $manager = mysqli_real_escape_string($dbc, $manager);
    $etc = mysqli_real_escape_string($dbc, $etc);
  
    $query = "INSERT INTO r_avans_vidan (data, istochnik_oplat, manager, suma, etc) VALUES ('$data', '$istochnik_oplat', '$manager', '$suma', '$etc')";

    $result = mysqli_query($dbc, $query) or die('Error querying database.');

    echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';
		  
    $sql = "SELECT * FROM r_avans_vidan order by pp desc limit 1"; 
    //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql)  or die(mysql_error());
    
    $table = "<table><tr><th>Дата видачі авансу</th><th>Джерело оплати</th><th>Менеджер</th><th>Сума авансу</th><th>Примітки</th></tr>";  
    while ($row = mysqli_fetch_array($result)){ //Формирование данных таблицы
        $table .= "<tr>"; 
        $table .= "<td>".$row['data']."</td>";
        $table .= "<td>".$row['istochnik_oplat']."</td>";
        $table .= "<td>".$row['manager']."</td>";
        $table .= "<td>".$row['suma']."</td>";
        $table .= "<td>".$row['etc']."</td>";
        $table .= "</tr>";
    }
    $table .= "</table> ";
    echo $table;
   
    mysqli_close($dbc);
}

if ($output_form) { //Если проверочная переменная = true, выводится форма для заполнения
?>
    <h5>Інформація, обов'язкова для заповнення, позначена *</h5>
    <form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<div>
            <div  class="wrapper">
                <span>Дата видачі авансу: *</span>
                <div class="bg">
                    <input type="date" name="data" class="input" value="<?php echo $data; ?>" >
                </div>
            </div>
            <div  class="wrapper">
                <span>Джерело виплати авансу: *</span>
                <div class="radio">
                    Банк&nbsp;<input type="radio" name="istochnik_oplat" value="банк" <?php if($istochnik_oplat=="банк"){echo 'checked';}?>>&nbsp;&nbsp;  
                    Каса&nbsp;<input type="radio" name="istochnik_oplat" value="каса" <?php if($istochnik_oplat=="каса"){echo 'checked';}?>> 
                </div>
            </div>
            <div  class="wrapper">
                <span>Менеджер: *</span>
                <div class="bg">
                    <select name="manager" class="select">
                        <option value="0">Оберіть менеджера</option>
                            <?php
                            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                            or die('Error connecting to MySQL server.');
                            mysqli_query($dbc, 'SET NAMES UTF8');
                            $choice = mysqli_query($dbc,"SELECT fio_kratko FROM manager ORDER BY fio_kratko");
                            while($row = mysqli_fetch_array($choice)){
                                echo "<option value=".$row['fio_kratko'].">".$row['fio_kratko']."</option>";
                            }
                            mysqli_close($dbc);
                            ?>
                    </select>			
                </div>
            </div>
            <div class="wrapper">
                <span>Сума авансу: *</span>
                <div class="bg">
                    <input type="text" name="suma" class="input" value="<?php echo $suma; ?>">
                </div>
            </div>
            <div  class="textarea_box">
                <span>Примітки:</span>
                <div class="bg">
                    <textarea name="etc" cols="52" rows="5" value="<?php echo $etc; ?>"></textarea>
                </div>
            </div>
            <input class="button" type="submit" value="OK" name="submit" /> 
	</div>
    </form>

<?php
  }
?>		

</div>		       
</body>
</html>
