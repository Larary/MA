<?php 
require_once('../include/startsession.php');
if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Будь-ласка, <a href="../admin/login.php">увійдіть</a> , для використання даної сторінки.</p>';
    exit();
}
if ($_SESSION['role_id']!=1) {
    echo '<p class="login">Ви не маєте прав для використання даної сторінки.</p></br>
    <a href="../admin/index.php">На головну сторінку.</a>';
    exit();
}
$title='Реєстрація користувача';
include ('../include/header.php'); ?>
<!-- Подключаем заголовок и меню-->

<div id="content">
    <h3>Адміністрування</h3>
    <h2>Реєстрація користувача</h2>

<?php
require_once('../include/dbconnect.php');
if (isset($_POST['submit'])) {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
    or die('Error connecting to MySQL server.');
    mysqli_query($dbc, 'SET NAMES UTF8');

    $fio = mysqli_real_escape_string($dbc, trim($_POST['fio']));
    $login = mysqli_real_escape_string($dbc, trim($_POST['login']));
    $pass1 = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
    $pass2 = mysqli_real_escape_string($dbc, trim($_POST['pass2']));
    $role = intval($_POST['role']);

    if (!empty($fio) && !empty($login) && !empty($pass1) && !empty($pass2) && !empty($role) && ($pass1 == $pass2)) {
        // Убеждаемся, что такого логина нет в базе
        $query = "SELECT * FROM users WHERE login = '$login'";
        $data = mysqli_query($dbc, $query);
        if (mysqli_num_rows($data) == 0) {
            // Логин уникальный, регистрируем пользователя
            $query = "INSERT INTO users (login, pass, fio, role_id) VALUES ('$login', SHA('$pass1'), '$fio', $role)";
            mysqli_query($dbc, $query);

            // Подтверждение регистрации
            echo '<h4>Нового користувача успішно додано.</h4>';

            mysqli_close($dbc);
            exit();
        }
        else {
            // Если такой логин уже есть, выводим текст ошибки
            echo '<p class="errortext">Користувач з даним логіном вже існує.</p>';
            $login = "";
        }
    }
    else {
        echo '<p class="errortext">Дані користувача заповнені не повністю.</p>';
    }
}

?>

    <!--Начало формы регистрации-->
    <form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div>
            <div class="wrapper">
                <span>ПІБ користувача:</span>
                <div class="bg">
                    <input type="text" name="fio" class="input" value="<?php if (!empty($fio)) echo $fio; ?>"/>
                </div>
            </div>
            <div class="wrapper">
                <span>Логін користувача:</span>
                <div class="bg">
                    <input type="text" name="login" class="input" value="<?php if (!empty($login)) echo $login; ?>" >
                </div>
            </div>
            <div class="wrapper">
                <span>Пароль:</span>
                <div class="bg">
                    <input type="password" name="pass1" class="input" >
                </div>
            </div>
            <div class="wrapper">
                <span>Пароль (повторити):</span>
                <div class="bg">
                    <input type="password" name="pass2" class="input" >
                </div>
            </div>
            <div class="wrapper">
                <span>Роль користувача:</span>
                <div class="bg">
                    <select name="role" class="select">
                    <option value="0">Оберіть роль користувача</option>
                    <?php
                        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                        or die('Error connecting to MySQL server.');
                        mysqli_query($dbc, 'SET NAMES UTF8');
                        $choice = mysqli_query($dbc,"SELECT * FROM user_role");
                        while($row = mysqli_fetch_array($choice)){
                            echo "<option value='".$row['id']."'>".$row['role']."</option>";
                        }
                        mysqli_close($dbc);
                    ?> 
                    </select>			
                </div>
            </div> 
        <input class="button" type="submit" value="Додати" name="submit" /> 
        </div>
    </form>
<!--Конец формы регистрации-->
</div>
</body>
</html>