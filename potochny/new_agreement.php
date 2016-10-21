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
$title='Додати договір до реєстру';
include ('../include/header.php'); ?>
<!-- Подключаем заголовок и меню--> 
  
<div id="content">
    <h3>Поточна діяльність</h3>
    <h2>Додати договір до реєстру</h2>
		
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
    //Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
    $rnz=$data_reestr=$zadacha=$adres=$summa_rabot=$dzerelo=$etc=null;
    $output_form = true;
}

else if (isset($_POST['submit'])){ //Проверка, была ли уже отправка данных
	
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
    $rnz = trim($_POST['rnz']); //И данные формы присваиваются соответствующим переменным
    if (empty($rnz)) { //Если переменная пустая, то данные формы не заполнены
        echo "<p class='errortext'>Не заповнено реєстровий номер договору.</p><br />";
        $output_form = true; //Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }
    $data_reestr = $_POST['data_reestr'];
    if (empty($data_reestr)) {
        echo "<p class='errortext'>Не вибрано дату договору.</p><br />";
        $output_form = true;
    }
    $vid_rabot = trim($_POST['vid_rabot']);
    if (empty($vid_rabot)) {
        echo "<p class='errortext'>Не вибрано вид робіт.</p><br />";
        $output_form = true;
    }
    $zakazchik = trim($_POST['zakazchik']);
    if (empty($zakazchik)) {
        echo "<p class='errortext'>Не вибрано замовника.</p><br />";
        $output_form = true;
    }
    $zadacha = trim($_POST['zadacha']);
    if (empty($zadacha)) {
        echo "<p class='errortext'>Не заповнено предмет договору.</p><br />";
        $output_form = true;
    }
    $manager = trim($_POST['manager']);
    if (empty($manager)) {
        echo "<p class='errortext'>Не вибрано менеджера.</p><br />";
        $output_form = true;
    }
    $gorod = trim($_POST['gorod']);
    if (empty($gorod)) {
        echo "<p class='errortext'>Не вибрано місце проведення робіт.</p><br />";
        $output_form = true;
    }
    $adres = trim($_POST['adres']);
    if (empty($adres)) {
        echo "<p class='errortext'>Не заповнено адресу проведення робіт.</p><br />";
        $output_form = true;
    }
    $summa_rabot = trim($_POST['summa_rabot']);
    if (empty($summa_rabot)) {
        echo "<p class='errortext'>Не заповнено суму робіт за договором.</p><br />";
        $output_form = true;
    }
    $tip_dzerela = trim($_POST['tip_dzerela']);
    $dzerelo = trim($_POST['dzerelo']);
    $manager_sdelki = trim($_POST['manager_sdelki']);
    $etc = trim($_POST['etc']);   
}
else {
    $output_form = true;
}
	
if (!$output_form){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');
    mysqli_query($dbc, 'SET NAMES UTF8');
	
    $rnz = mysqli_real_escape_string($dbc, $rnz);
    $data_reestr = mysqli_real_escape_string($dbc, $data_reestr);
    $vid_rabot = mysqli_real_escape_string($dbc, $vid_rabot);
    $zakazchik = mysqli_real_escape_string($dbc, $zakazchik);
    $zadacha = mysqli_real_escape_string($dbc, $zadacha);
    $manager = mysqli_real_escape_string($dbc, $manager);
    $gorod = mysqli_real_escape_string($dbc, $gorod);
    $adres = mysqli_real_escape_string($dbc, $adres);
    $summa_rabot = str_replace(',','.',$summa_rabot);//Если пользователь ввел в цифре запятую, меняем на точку
    $summa_rabot = floatval($summa_rabot);//Приводим переменную к типу с плавающей точкой во избежание посторонних символов
    $tip_dzerela = mysqli_real_escape_string($dbc, $tip_dzerela);
    $dzerelo = mysqli_real_escape_string($dbc, $dzerelo);
    $manager_sdelki = mysqli_real_escape_string($dbc, $manager_sdelki);
    $etc = mysqli_real_escape_string($dbc, $etc);
  
    $query = "INSERT INTO r_reestr1 (rnz, data_reestr, vid_rabot, zakazchik, zadacha, manager, gorod, 
	adres, summa_rabot, tip_dzerela, dzerelo, manager_sdelki, etc) VALUES ('$rnz', '$data_reestr', '$vid_rabot', '$zakazchik', '$zadacha', '$manager', '$gorod', 
	'$adres', '$summa_rabot', '$tip_dzerela', '$dzerelo', '$manager_sdelki', '$etc')";

    $result = mysqli_query($dbc, $query) or die('Error querying database.');

    echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';
		  
    $sql = "SELECT * FROM r_reestr1 order by pp desc limit 1"; 
     //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql) or die(mysql_error());
    $table = "<table><tr><th>Номер договору</th><th>Дата договору</th><th>Вид робіт</th><th>Замовник</th><th>Предмет договору</th>
			<th>Менеджер</th><th>Місто робіт</th><th>Адреса робіт</th><th>Тип джерела інформації</th><th>Джерело інформації</th>
			<th>Менеджер угоди</th><th>Примітки</th></tr>";
	   
    while ($row = mysqli_fetch_array($result)) { //Формирование данных таблицы
        $table .= "<tr>"; 
        $table .= "<td>".$row['rnz']."</td>";
        $table .= "<td>".$row['data_reestr']."</td>";
        $table .= "<td>".$row['vid_rabot']."</td>";
        $table .= "<td>".$row['zakazchik']."</td>";
        $table .= "<td>".$row['zadacha']."</td>";
        $table .= "<td>".$row['manager']."</td>";
        $table .= "<td>".$row['gorod']."</td>";
        $table .= "<td>".$row['adres']."</td>";
        $table .= "<td>".$row['summa_rabot']."</td>";
        $table .= "<td>".$row['tip_dzerela']."</td>";
        $table .= "<td>".$row['dzerelo']."</td>";
        $table .= "<td>".$row['manager_sdelki']."</td>";
        $table .= "<td>".$row['etc']."</td>";
        $table .= "</tr>";
    }
    $table .= "</table> ";
    echo $table;
   
    mysqli_close($dbc);
}

if ($output_form) { //Если проверочная переменная = true, выводится форма для заполнения
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');
    mysqli_query($dbc, 'SET NAMES UTF8');
?>
    <h5>Інформація, обов'язкова для заповнення, позначена *</h5>
    <form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<div>
            <div  class="wrapper">
                <span>Реєстровий номер договору: *</span>
                <div class="bg">
                    <input type="text" name="rnz" class="input" value="<?php echo $rnz; ?>"/>
                </div>
            </div>
            <div  class="wrapper">
                <span>Дата договору: *</span>
                <div class="bg">
                    <input type="date" name="data_reestr" class="input" value="<?php echo $data_reestr; ?>" >
                </div>
            </div>
            <div  class="wrapper">
                <span>Вид робіт: *</span>
                <div class="bg">
                    <select name="vid_rabot" class="select">
                        <option value="0">Оберіть вид робіт</option>
                            <?php
                            $choice = mysqli_query($dbc,"SELECT * FROM vid_rabot");
                            while($row = mysqli_fetch_array($choice)){
                                echo "<option value=".$row['vid_rabot_kratko'].">".$row['vid_rabot_full']."</option>";
                            }
                            ?>
                    </select>			
                </div>
            </div>		
            <div  class="wrapper">
                <span>Замовник робіт: *</span>
                <div class="bg">
                    <select name="zakazchik" class="select">
                        <option value="0">Оберіть замовника</option>
                            <?php
                            $choice = mysqli_query($dbc,"SELECT name FROM zakazchik ORDER BY name");
                            while($row = mysqli_fetch_array($choice)){
                                echo "<option value=".$row['name'].">".$row['name']."</option>";
                            }
                            ?>
                    </select>			
                </div>
            </div>
            <div  class="textarea_box">
                <span>Предмет договору: *</span>
                <div class="bg">
                    <textarea name="zadacha" cols="52" rows="3"  value="<?php echo $zadacha; ?>"></textarea>
                </div>
            </div>
            <div  class="wrapper">
                <span>Менеджер: *</span>
                <div class="bg">
                    <select name="manager" class="select">
                        <option value="0">Оберіть менеджера</option>
                            <?php
                            $choice = mysqli_query($dbc,"SELECT * FROM manager ORDER BY fio_kratko");
                            while($row = mysqli_fetch_array($choice)){
                                echo "<option value='".$row['pp']."'>".$row['fio_kratko']."</option>";
                            }
                            ?>
                    </select>			
                </div>
            </div>
            <div  class="wrapper">
                <span>Місце проведення робіт: *</span>
                <div class="bg">
                    <select id="oblast" name="oblast" class="select" >
                        <option value="">Оберіть область</option>
                            <?php
                            $choice = mysqli_query($dbc,"SELECT * FROM oblast");
                            while($row = mysqli_fetch_array($choice)){
                                echo "<option value='".$row['pp']."'>".$row['oblast']."</option>";
                            } 
                            ?>
                    </select></br>
                </div>
            </div>
            <div  class="wrapper">
                <span>&nbsp;</span>
                <div class="bg">
                    <select id="region" name="region" class="select" >
                        <option value="">Оберіть район</option>
                    </select>
                </div>
            </div>	
            <div  class="wrapper">
                <span>&nbsp;</span>
                <div class="bg">
                    <select id="gorod" name="gorod" class="select" >
                        <option value="">Оберіть місто</option>
                    </select>
                </div>
            </div>
            <div  class="textarea_box">
                <span>Адреса проведення робіт: *</span>
                <div class="bg">
                    <textarea name="adres" cols="52" rows="2"  value="<?php echo $adres; ?>"></textarea>
                </div>
            </div>		
            <div  class="wrapper">
                <span>Вартість робіт за договором: *</span>
                <div class="bg">
                    <input type="text" name="summa_rabot" class="input" value="<?php echo $summa_rabot; ?>">
                </div>
            </div>
            <div  class="wrapper">
                <span>Тип джерела інформації:</span>
                <div class="bg">
                    <select name="tip_dzerela" class="select">
                        <option value="0">Оберіть джерело інформації</option>
                            <?php
                            $choice = mysqli_query($dbc,"SELECT * FROM dzerelo");
                            while($row = mysqli_fetch_array($choice)){
                                echo "<option value='".$row['tip_dzerela']."'>".$row['tip_dzerela']."</option>";
                            }
                            ?>
                    </select>			
                </div>
            </div>
            <div  class="wrapper">
                <span>Джерело інформації детально:</span>
                <div class="bg">
                    <input type="text" name="dzerelo" class="input" value="<?php echo $dzerelo; ?>">
                </div>
            </div>
            <div  class="wrapper">
                <span>Менеджер угоди:</span>
                <div class="bg">
                    <select name="manager_sdelki" class="select">
                        <option value="0">Оберіть менеджера</option>
                            <?php
                            $choice = mysqli_query($dbc,"SELECT * FROM manager ORDER BY fio_kratko");
                            while($row = mysqli_fetch_array($choice)){
                                echo "<option value='".$row['pp']."'>".$row['fio_kratko']."</option>";
                            }
                            ?>
                    </select>			
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
    mysqli_close($dbc);
}
?>		
		       
</div>
   
<script type="text/javascript" charset="utf-8">
$(function() {
$("#region").remoteChained({
        parents : "#oblast",
        url : "../include/select_region.php",
        loading : "--"
    });
$("#gorod").remoteChained({
        parents : "#region",
        url : "../include/select_gorod.php",
        loading : "--"
    });
});	
</script>
</body>
</html>
