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
$title='Менеджери';
include ('../include/header.php'); ?>
<!-- Подключаем заголовок и меню-->

<div id="content">
    <h3>Довідники</h3>
    <h2>Менеджери</h2>
        
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
//Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
    $fio_full=$fio_kratko=$etc=null;
    $output_form = true;
}
else if (isset($_POST['submit'])){ //Проверка, была ли уже отправка данных
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
    $fio_full = trim($_POST['fio_full']); //И данные формы присваиваются соответствующим переменным
    if (empty($fio_full)) { //Если переменная пустая, то данные формы не заполнены
        echo "<p class='errortext'>Не заповнено ПІБ менеджера у повній формі.</p><br />";
        $output_form = true; //Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }
    $fio_kratko = trim($_POST['fio_kratko']);
    if (empty($fio_kratko)) {
        echo "<p class='errortext'>Не заповнено ПІБ менеджера в скороченій формі.</p><br />";
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

    $fio_full = mysqli_real_escape_string($dbc, $fio_full);
    $fio_kratko = mysqli_real_escape_string($dbc, $fio_kratko);
    $etc = mysqli_real_escape_string($dbc, $etc);

    $query = "INSERT INTO manager (fio_full, fio_kratko, etc) VALUES ('$fio_full', '$fio_kratko', '$etc')";

    $result = mysqli_query($dbc, $query) or die('Error querying database.');

    echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';

    $sql = "SELECT * FROM manager order by pp desc limit 1"; 
    //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql) or die(mysql_error());
    $table = "<table><tr><th>ПІБ менеджера повністю</th><th>ПІБ менеджера скорочено</th><th>Примітки</th></tr>";

    while ($row = mysqli_fetch_array($result)){//Формирование данных таблицы
        $table .= "<tr>"; 
        $table .= "<td>".$row['fio_full']."</td>";
        $table .= "<td>".$row['fio_kratko']."</td>";
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
            <div class="wrapper">
                <span>ПІБ менеджера повністю: *</span>
                <div class="bg">
                    <input type="text" name="fio_full" class="input" value="<?php echo $fio_full; ?>"/>
                </div>
            </div>
            <div class="wrapper">
                <span>ПІБ менеджера скорочено: *</span>
                <div class="bg">
                    <input type="text" name="fio_kratko" class="input" value="<?php echo $fio_kratko; ?>"/>
                </div>
            </div>
            <div class="textarea_box">
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