<?php  
require_once('../include/startsession.php');
if (!isset($_SESSION['user_id'])) {
    echo '<p class="login">Будь-ласка, <a href="../admin/login.php">увійдіть</a> , для використання даної сторінки.</p>';
    exit();
  }
$title='Пошук договорів за номерами';
	  include ('../include/header.php'); ?>
     <!-- Подключаем заголовок и меню--> 
  
<div id="content">
    <h3>Звітність</h3>
	<h2>Пошук договорів за номерами</h2>
	<h5>Для пошуку декількох договорів введіть їх номери через кому або пробіл</h5>

<form id="Form" method="get" action="agr_reestr2.php">
    <div>
		<div  class="wrapper">
			<span>Введіть номери договорів:</span>
			<div class="bg"><input type="text" id="usersearch" name="usersearch" class="input" ></div>
		</div>
		<div  class="wrapper">
			
			<div class="bg"><input type="hidden" id="h2" name="h2" value="<h2>Пошук договорів за номерами</h2>" ></div>
		</div>
		<div  class="wrapper">
			
			<div class="bg"><input type="hidden" id="h4" name="h4" value="" ></div>
		</div>
		
		<input class="button" type="submit" value="OK" name="submit" />
	</div>
</form>

</body>
</html>
