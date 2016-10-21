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
    <h2>Видалення користувача</h2>

    <form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<?php
require_once('../include/dbconnect.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
or die('Error connecting to MySQL server.');
mysqli_query($dbc, 'SET NAMES UTF8');
// Удаление пользователя после нажатия Видалити
if (isset($_POST['submit'])) {
    foreach ($_POST['todelete'] as $delete_id) {
        $query = "DELETE FROM users WHERE id = $delete_id";
        mysqli_query($dbc, $query)
        or die('Error querying database.');
    }
    echo '<h4>Користувача видалено.</h4><br />';
}

// Вывод таблицы с пользователями
$query = "SELECT user_id, login, fio, role FROM users INNER JOIN user_role ON users.role_id=user_role.id";
$result = mysqli_query($dbc, $query);
$table = "<table><tr><th>Id</th><th>Логін</th><th>ПІБ</th><th>Роль користувача</th><th>Видалити</th></tr>";

while ($row = mysqli_fetch_array($result)) {
    $table .= "<tr>"; 
    $table .= "<td>".$row['user_id']."</td>";
    $table .= "<td>".$row['login']."</td>";
    $table .= "<td>".$row['fio']."</td>";
    $table .= "<td>".$row['role']."</td>";
    $table .= "<td><input type='checkbox' name='todelete[]'/></td>";
    $table .= "</tr>";
}
$table .= "</table> ";
echo $table;
mysqli_close($dbc);
?>

        <input class="button" type="submit" name="submit" value="Видалити" />
    </form>
</div>
</body>
</html>