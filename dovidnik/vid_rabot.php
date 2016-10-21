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
$title='Види робіт';
include ('../include/header.php'); ?>
<!-- Подключаем заголовок и меню-->
    
<div id="content">
    <h3>Довідники</h3>
    <h2>Види робіт</h2>
		
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
//Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
    $vid_rabot_kratko=$vid_rabot_full=null;
    $output_form = true;}
else if (isset($_POST['submit'])){ //Проверка, была ли уже отправка данных
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
    $vid_rabot_kratko = trim($_POST['vid_rabot_kratko']); //И данные формы присваиваются соответствующим переменным
    if (empty($vid_rabot_kratko)) { //Если переменная пустая, то данные формы не заполнены
        echo "<p class='errortext'>Не заповнено скорочену назву виду робіт.</p><br />";
        $output_form = true; //Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }
    $vid_rabot_full = trim($_POST['vid_rabot_full']); 
    if (empty($vid_rabot_full)) { 
        echo "<p class='errortext'>Не заповнено повну назву виду робіт.</p><br />";
        $output_form = true; 
    }
    $tip_rabot = trim($_POST['tip_rabot']); 
    if (empty($tip_rabot)) { 
        echo "<p class='errortext'>Не вибрано тип робіт.</p><br />";
        $output_form = true; 
    }    
}
else {
    $output_form = true;
}
	   
if (!$output_form){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');
    mysqli_query($dbc, 'SET NAMES UTF8');
	
    $vid_rabot_kratko = mysqli_real_escape_string($dbc, $vid_rabot_kratko);
    $vid_rabot_full = mysqli_real_escape_string($dbc, $vid_rabot_full);
    $tip_rabot = mysqli_real_escape_string($dbc, $tip_rabot);
	
    $query = "INSERT INTO vid_rabot (vid_rabot_kratko, vid_rabot_full, tip_rabot) VALUES ('$vid_rabot_kratko', '$vid_rabot_full', '$tip_rabot')";
    $result = mysqli_query($dbc, $query) or die('Error querying database.');

    echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';
		  
    $sql = "SELECT * FROM vid_rabot order by pp desc limit 1"; 
    //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql) or die(mysql_error());
    $table = "<table><tr><th>Вид робіт коротко</th><th>Вид робіт повністю</th><th>Тип робіт</th></tr>";
    //Формирование данных таблицы   
    while ($row = mysqli_fetch_array($result)) {
        $table .= "<tr>"; 
        $table .= "<td>".$row['vid_rabot_kratko']."</td>";
	$table .= "<td>".$row['vid_rabot_full']."</td>";
	$table .= "<td>".$row['tip_rabot']."</td>";
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
            <div class="wrapper">
                <span>Вид робіт скорочено: *</span>
                <div class="bg">
                    <input type="text" name="vid_rabot_kratko" class="input" value="<?php echo $vid_rabot_kratko; ?>"/>
                </div>
            </div>
            <div class="wrapper">
                <span>Вид робіт повністю: *</span>
                <div class="bg">
                    <input type="text" name="vid_rabot_full" class="input" value="<?php echo $vid_rabot_full; ?>"/>
                </div>
            </div>
            <div class="wrapper">
                <span>Тип робіт: *</span>
                <div class="bg">
                    <select name="tip_rabot" class="select">
                    <option value="0">Оберіть тип робіт</option>
                    <?php
                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');
                    mysqli_query($dbc, 'SET NAMES UTF8');
                    $choice = mysqli_query($dbc,"SELECT * FROM tip_rabot");
                    while($row = mysqli_fetch_array($choice)){
                        echo "<option value='".$row['tip_rabot_kratko']."'>".$row['tip_rabot_full']."</option>";
                    }
                    mysqli_close($dbc);
                    ?>
                    </select>			
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
