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
$title='Звіт про обсяги діяльності, витрати та прибуток';
include ('../include/header.php'); ?>
<!-- Подключаем заголовок и меню--> 
 
<div id="content">
    <h3>Звітність</h3>
<?php 
    $h2 = '<h2>Загальний звіт про обсяги діяльності, витрати та прибуток</h2>';
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

    $query1 = "SELECT SUM(summa_rabot), SUM(zatraty_plan), SUM(premia), SUM(nalogi), SUM(geodezia), SUM(agentskie), SUM(bonus), SUM(pribil_plan) 
            FROM r_reestr1 INNER JOIN r_reestr2 ON r_reestr1.rnz=r_reestr2.rnz WHERE data_reestr BETWEEN '$date_begin' AND '$date_end'";
    $result1 = mysqli_query($dbc, $query1) or die('Error querying database1.');
    while ($row = mysqli_fetch_array($result1)){
        $summa_rabot = $row['SUM(summa_rabot)'];
        $zatraty_plan = $row['SUM(zatraty_plan)'];
        $premia = $row['SUM(premia)'];
        $nalogi = $row['SUM(nalogi)'];
        $geodezia = $row['SUM(geodezia)'];
        $agentskie = $row['SUM(agentskie)'];
        $bonus = $row['SUM(bonus)'];
        $pribil_plan = $row['SUM(pribil_plan)'];
    }
    $query2 = "SELECT SUM(suma_zatrat) FROM r_reestr1 INNER JOIN r_zatraty_proekt ON r_reestr1.rnz=r_zatraty_proekt.rnz  
        WHERE data_reestr BETWEEN '$date_begin' AND '$date_end'";
    $result2 = mysqli_query($dbc, $query2) or die('Error querying database2.');
    while ($row = mysqli_fetch_array($result2)){
        $suma_zatrat = $row['SUM(suma_zatrat)'];
    }
    $query3 = "SELECT SUM(oplata_fakt) FROM r_reestr1 INNER JOIN r_oplaty ON r_reestr1.rnz=r_oplaty.rnz 
            WHERE data_reestr BETWEEN '$date_begin' AND '$date_end'";
    $result3 = mysqli_query($dbc, $query3) or die('Error querying database2.');
    while ($row = mysqli_fetch_array($result3)){
        $oplata_fakt = $row['SUM(oplata_fakt)'];
    }
	
    mysqli_close($dbc);

    $pribil_fakt = $pribil_plan + $zatraty_plan - $suma_zatrat;
    $debt = $summa_rabot - $oplata_fakt;
    $h4 = "<h4>Звіт за період з ".$date_begin." по ".$date_end."</h4>";
    echo $h4;
?>
    <div id="report">	
<?php
    $table = '<table >
	<tr><th>Найменування показника</th><th>Сума, грн.</th></tr>
	<tr><td id="left_al">Укладено договорів на суму</td><td>'.number_format($summa_rabot,2,',',' ').'</td></tr>
	<tr><td id="left_al">Планові витрати</td><td>'.number_format($zatraty_plan,2,',',' ').'</td></tr>
	<tr><td id="left_al">Фактичні витрати</td><td>'.number_format($suma_zatrat,2,',',' ').'</td></tr>
	<tr><td id="left_al">Інші витрати:</td><td></td></tr>
	<tr><td id="sub-row">премія</td><td>'.number_format($premia,2,',',' ').'</td></tr>
	<tr><td id="sub-row">податки</td><td>'.number_format($nalogi,2,',',' ').'</td></tr>
	<tr><td id="sub-row">геодезія</td><td>'.number_format($geodezia,2,',',' ').'</td></tr>
	<tr><td id="sub-row">агентські</td><td>'.number_format($agentskie,2,',',' ').'</td></tr>
	<tr><td id="sub-row">бонус</td><td>'.number_format($bonus,2,',',' ').'</td></tr>
	<tr><td id="left_al">Плановий прибуток</td><td>'.number_format($pribil_plan,2,',',' ').'</td></tr>
	<tr><td id="left_al">Фактичний прибуток</td><td>'.number_format($pribil_fakt,2,',',' ').'</td></tr>
	<tr><td id="left_al">Оплачено за договорами</td><td>'.number_format($oplata_fakt,2,',',' ').'</td></tr>
	<tr><td id="left_al">Заборгованість замовників</td><td>'.number_format($debt,2,',',' ').'</td></tr>
	</table>';
    echo $table;
?>
    </div>

    <a href="#" id ="export" role='button'>Зберегти звіт в CSV</a></br></br>
<?php 
    $pdf = $h2.$h4.htmlspecialchars($table);
    echo '<a href="../include/pdf.php?pdf='.$pdf.'">Зберегти звіт в PDF</a>';                
	   
}

if ($output_form) { //Если проверочная переменная = true, выводится форма для заполнения
?>
    <h4>Оберіть дати початку та закінчення періоду</h4>
    <form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div>
            <div class="wrapper">
                <span>Дата початку періоду:</span>
                <div class="bg">
                    <input type="date" name="date_begin" class="input" >
                </div>
            </div>
            <div class="wrapper">
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
    
<script type="text/javascript" src="../js/save_csv.js" ></script>

</body>
</html>
