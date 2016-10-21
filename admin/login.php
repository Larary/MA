<?php
require_once('../include/dbconnect.php');

session_start();

// Clear the error message
$error_msg = "";

// If the user isn't logged in, try to log them in
if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
        // Connect to the database
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        mysqli_query($dbc, 'SET NAMES UTF8');
        // Grab the user-entered log-in data
        $user_login = mysqli_real_escape_string($dbc, trim($_POST['login']));
        $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

        if (!empty($user_login) && !empty($user_password)) {
            // Look up the username and password in the database
            $query = "SELECT * FROM users WHERE login = '$user_login' AND pass = SHA('$user_password')";
            $data = mysqli_query($dbc, $query);

            if (mysqli_num_rows($data) == 1) {
            // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
                $row = mysqli_fetch_array($data);
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['login'] = $row['login'];
                $_SESSION['fio'] = $row['fio'];
                $_SESSION['role_id'] = $row['role_id'];
                setcookie('user_id', $row['user_id']);    
                setcookie('login', $row['login']);  
                setcookie('fio', $row['fio']);
                setcookie('role_id', $row['role_id']);
                $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
                header('Location: ' . $home_url);
            }
            else {
                // The username/password are incorrect so set an error message
                $error_msg = 'Для входу потрібно ввести логін та пароль.';
            }
        }
        else {
            // The username/password weren't entered so set an error message
            $error_msg = 'Для входу потрібно ввести логін та пароль.';
        }
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//UK"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="uk" lang="uk">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="../css/style.css"/>
    <script type="text/javascript" src="../js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="../js/date_script.js"></script>
    <title>MA - Вхід</title>
</head>
<body>
    <div id="main">
        <header>
            <div class="wrapper">
                <span class="date">Monday, June 6, 2011  &nbsp; &nbsp; 17:19</span>
            </div>
            <div id="logo">
                <div id="logo_text">
                    <h1><a href="index.php">Management&nbsp;&nbsp;<span class="logo_colour">Accounting</span></a></h1>    
                </div>
            </div>
        </header>
<?php
// If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
if (empty($_SESSION['user_id'])) {
    echo '<p class="errortext">' . $error_msg . '</p>';
?>
    <h2>MA - Вхід</h2>
    <form id="Form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div>
            <div class="wrapper">
                <span>Логін:</span>
                <div class="bg">
                    <input type="text" name="login" class="input" value="<?php if (!empty($user_login)) {echo $user_login;} ?>">
                </div>
            </div>
            <div class="wrapper">
                <span>Пароль:</span>
                <div class="bg"><input type="password" name="password" class="input" ></div>
            </div>
            <input class="button" type="submit" value="Увійти" name="submit" />
        </div>
    </form>

<?php
}
else {
    // Confirm the successful log-in
    echo('<h5>Ви увійшли як ' .$_SESSION['fio']. ', логін '.$_SESSION['login'].'.</h5></br></br>
    <a href="../admin/logout.php">Вихід</a>');
}
?>
    </div>
</body>
</html>
