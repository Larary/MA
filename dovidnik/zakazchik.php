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
$title='Замовники';
include ('../include/header.php'); ?>
<!-- Подключаем заголовок и меню-->
    
<div id="content">
    <h3>Довідники</h3>
    <h2>Замовники</h2>
		
<?php
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД
if (!isset($_POST['submit'])) { //Если ОК еще не нажимали и данные не отправляли
//Присваиваем переменным нулевые значения, чтобы выводимая пустая форма не выдавала ошибки
    $name=$kontakt=$address=$phone=$email=$etc=null;
    $output_form = true;}
 else if (isset($_POST['submit'])){ //Проверка, была ли уже отправка данных
    $output_form = false; //Если данные формы отправлялись, проверочной переменной присваивается false
    $name = trim($_POST['name']); //И данные формы присваиваются соответствующим переменным
    if (empty($name)) { //Если переменная пустая, то данные формы не заполнены
        echo "<p class='errortext'>Не заповнено назву або ПІБ замовника.</p><br />";
        $output_form = true; //Если переменная пустая, проверочная переменная = true, и форма вернется на дозаполнение
    }
    $kontakt = trim($_POST['kontakt']); //Остальные данные не обязательны для заполнения и их наличие не проверяется
    $address = trim($_POST['address']);	
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $etc = trim($_POST['etc']);
}
else {
    $output_form = true;
}
	
if (!$output_form){
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');
    mysqli_query($dbc, 'SET NAMES UTF8');
	
    $name = mysqli_real_escape_string($dbc, $name);
    $kontakt = mysqli_real_escape_string($dbc, $kontakt);
    $address = mysqli_real_escape_string($dbc, $address);
    $phone = mysqli_real_escape_string($dbc, $phone);
    $email = mysqli_real_escape_string($dbc, $email);
    $etc = mysqli_real_escape_string($dbc, $etc);
  
    $query = "INSERT INTO zakazchik (name, kontakt, address, phone, email, etc) VALUES ('$name', '$kontakt', '$address', '$phone', '$email', '$etc')";
    $result = mysqli_query($dbc, $query) or die('Error querying database.');

    echo '<h4>Дані успішно внесені. Ви додали:</h4><br />';
		  
    $sql = "SELECT * FROM zakazchik order by pp desc limit 1"; 
    //Вывод подтверждающей строки об успешном внесении данных в табличной форме
    $result = mysqli_query($dbc, $sql) or die(mysql_error());
   //Формирование заголовка таблицы
    $table = "<table><tr><th>Назва/ПІБ замовника</th><th>Контактна особа</th><th>Адреса</th><th>Телефон</th><th>E-mail</th><th>Примітки</th></tr>";
    //Формирование данных таблицы  
    while ($row = mysqli_fetch_array($result)){
        $table .= "<tr>"; 
        $table .= "<td>".$row['name']."</td>";
        $table .= "<td>".$row['kontakt']."</td>";
        $table .= "<td>".$row['address']."</td>";
        $table .= "<td>".$row['phone']."</td>";
        $table .= "<td>".$row['email']."</td>";
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
                <span>Назва/ПІБ замовника: *</span>
                <div class="bg">
                    <input type="text" name="name" class="input" value="<?php echo $name; ?>"/>
                </div>
            </div>
            <div  class="wrapper">
                <span>Контактна особа:</span>
                <div class="bg">
                    <input type="text" name="kontakt" class="input" value="<?php echo $kontakt; ?>" >
                </div>
            </div>
            <div  class="wrapper">
                <span>Адреса замовника:</span>
                <div class="bg">
                    <input type="text" name="address" class="input" value="<?php echo $address; ?>">
                </div>
            </div>
            <div  class="wrapper">
                <span>Телефон замовника:</span>
                <div class="bg">
                    <input type="text" name="phone" class="input" value="<?php echo $phone; ?>">
                </div>
            </div>
            <div  class="wrapper">
                <span>E-mail замовника:</span>
                <div class="bg">
                    <input type="text" name="email" class="input" value="<?php echo $email; ?>">
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
