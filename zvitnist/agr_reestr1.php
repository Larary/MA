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
$title='Реєстр договорів, укладених за період';
include ('../include/header.php'); ?>
<!-- Подключаем заголовок и меню--> 
  
<div id="content">
    <h3>Звітність</h3>
<?php	
    $h2 = '<h2>Реєстр договорів, укладених за період</h2>';
    echo $h2;
	
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
if (isset($_POST['submit'])){ //Проверка, была ли уже отправка данных
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
	
    $date_begin = $_POST['date_begin'];
    if (empty($date_begin)) {
        echo "<p class='errortext'>Не вибрано дату початку періоду.</p><br />";
        $output_form = true;
    }
    $date_end = $_POST['date_end'];
    if (empty($date_end)) {
        echo "<p class='errortext'>Не вибрано дату закінчення періоду.</p><br />";
        $output_form = true;
    }   
}
else {
    $output_form = true;
}
	
if (!$output_form){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');
    mysqli_query($dbc, 'SET NAMES UTF8');
	
    $date_begin = mysqli_real_escape_string($dbc, $date_begin);
    $date_end = mysqli_real_escape_string($dbc, $date_end);
  
    $query = "SELECT rnz FROM r_reestr1 WHERE data_reestr BETWEEN '$date_begin' AND '$date_end'";
    $result = mysqli_query($dbc, $query) or die('Error querying database.');
    
    $user_search = array();  
    while ($row = mysqli_fetch_array($result)){
	$user_search[$row['rnz']] = $row['rnz'];
    }
    $user_search=implode(" ",$user_search);
    echo $user_search;
    $h4 = "<h4>Звіт за період з ".$date_begin." по ".$date_end."</h4>";
	
    $url="../zvitnist/agr_reestr2.php?usersearch=".$user_search."&h2=".$h2."&h4=".$h4;

    echo '<script type="text/javascript">'; 
    echo 'window.location.href="'.$url.'";'; 
    echo '</script>';  
}

if ($output_form) { //Если проверочная переменная = true, выводится форма для заполнения
?>
<h4>Оберіть дати початку та закінчення періоду</h4>
<form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div>
        <div  class="wrapper">
            <span>Дата початку періоду:</span>
            <div class="bg">
                <input type="date" name="date_begin" class="input" >
            </div>
        </div>
        <div  class="wrapper">
            <span>Дата закінчення періоду:</span>
            <div class="bg">
                <input type="date" name="date_end" class="input" >
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
