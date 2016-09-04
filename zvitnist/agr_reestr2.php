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
require_once('../include/dbconnect.php'); //Подключаем файл с параметрами подключения к БД

// Функция строит запрос по номерам договоров, введенным для поиска
function build_query($user_search, $sort) {
    $search_query = "SELECT * FROM r_reestr1";

    // Разделение введенных номеров договоров из строки на отдельные номера
    $clean_search = str_replace(',', ' ', $user_search);
    $search_words = explode(' ', $clean_search);
    $final_search_words = array();
    if (count($search_words) > 0) {
		foreach ($search_words as $word) {
			if (!empty($word)) {
				$final_search_words[] = $word;
			}
		}
    }
    // Соединение номеров договоров для поиска в правильной форме и их добавление к WHERE
    $where_list = array();
    if (count($final_search_words) > 0) {
		foreach($final_search_words as $word) {
			$where_list[] = "rnz LIKE '%$word%'";
		}
    }
    $where_clause = implode(' OR ', $where_list);
    if (!empty($where_clause)) {
		$search_query .= " WHERE $where_clause";
    }
    // Опции сортировки выбранных договоров
    switch ($sort) {
    case 1:
		$search_query .= " ORDER BY rnz";
		break;
    case 2:
		$search_query .= " ORDER BY rnz DESC";
		break;
    case 3:
		$search_query .= " ORDER BY data_reestr";
		break;
    case 4:
		$search_query .= " ORDER BY data_reestr DESC";
		break;
    case 5:
		$search_query .= " ORDER BY zakazchik";
		break;
    case 6:
		$search_query .= " ORDER BY zakazchik DESC";
		break;
    case 7:
		$search_query .= " ORDER BY summa_rabot";
		break;
    case 8:
		$search_query .= " ORDER BY summa_rabot DESC";
		break;    
    default:
      // Если нет специально выбранной сортировки
    }
    return $search_query;
}

// Функция строит заголовки таблицы в виде ссылок, по которым можно сортировать
function generate_sort_links($user_search, $sort, $h2, $h4, $res_pp) {
    $sort_links = '';

    switch ($sort) {
    case 1:
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=2">Номер договору</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=3">Дата договору</a></th><th>Вид робіт</th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=5">Замовник</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=7">Сума робіт</a></th>';
		break;
    case 3:
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=1">Номер договору</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=4">Дата договору</a></th><th>Вид робіт</th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=5">Замовник</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=7">Сума робіт</a></th>';
		break;
    case 5:
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=1">Номер договору</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=3">Дата договору</a></th><th>Вид робіт</th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=6">Замовник</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=7">Сума робіт</a></th>';
		break;
	case 7:
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=1">Номер договору</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=3">Дата договору</a></th><th>Вид робіт</th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=5">Замовник</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=8">Сума робіт</a></th>';
		break;  
    default:
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=1">Номер договору</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=3">Дата договору</a></th><th>Вид робіт</th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=5">Замовник</a></th>';
		$sort_links .= '<th><a style="color: white" href = "'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort=7">Сума робіт</a></th>';
    }
    return $sort_links;
}

// Функция создает ссылки для разбивки вывода на страницы и перехода по страницам
function generate_page_links($user_search, $sort, $cur_page, $num_pages, $h2, $h4, $res_pp) {
    $page_links = '';
    // Если текущая страница не первая, создается ссылка стрелкой на предыдущую страницу
    if ($cur_page > 1) {
		$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort='.$sort.'&page='.($cur_page - 1).'"><-</a>';
    }
    else {
		$page_links .= '<- ';
    }
    // Генерируем ссылки на страницы
    for ($i = 1; $i <= $num_pages; $i++) {
		if ($cur_page == $i) {
			$page_links .= ' <b>' . $i.'</b>';
		}
		else {
			$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort='.$sort.'&page='.$i.'"> '.$i.' </a>';
		}
    }
    // Если текущая страница не последняя, создается ссылка стрелкой на следующую страницу
    if ($cur_page < $num_pages) {
		$page_links .= '<a href="'.$_SERVER['PHP_SELF'].'?usersearch='.$user_search.'&h2='.$h2.'&h4='.$h4.'&res_pp='.$res_pp.'&sort='.$sort.'&page='.($cur_page + 1).'">-></a>';
    }
    else {
		$page_links .= ' ->';
    }
    return $page_links;
}

//Начало программы. Получаем данные из GET  
$h2 = $_GET['h2'];
echo $h2;
$h4 = $_GET['h4'];
echo $h4;
$sort = isset($_GET['sort']) ? $_GET['sort'] : "";
$user_search = $_GET['usersearch'];
?>

<form action="" method="get"> <!-- res_pp = results_per_page = количество строк на странице  -->
<div>
<span>Кількість рядків на сторінці:</span>
    <select name="res_pp" id="res_pp" onchange="resultsPerPage()"><!--document.getElementById('res_pp').submit()--> 
        <option value="10"<?php if(isset($_GET['res_pp'])){if($_GET['res_pp']==10) echo 'selected="selected"';}?>>10</option>
        <option value="20"<?php if(isset($_GET['res_pp'])){if($_GET['res_pp']==20) echo 'selected="selected"';}?>>20</option>
        <option value="50"<?php if(isset($_GET['res_pp'])){if($_GET['res_pp']==50) echo 'selected="selected"';}?>>50</option>
		<option value="100"<?php if(isset($_GET['res_pp'])){if($_GET['res_pp']==100) echo 'selected="selected"';}?>>100</option>
		<option value="all"<?php if(isset($_GET['res_pp'])){if($_GET['res_pp']=='all') echo 'selected="selected"';}?>>Всі</option>
    </select>
</div>
</form>
<?php
// Получение из GET номера страницы; если номер не получен, это 1-я страница
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
$res_pp = isset($_GET['res_pp']) ? $_GET['res_pp'] : 10;  // количество строк на странице
$skip = is_numeric($res_pp) ? (($cur_page - 1) * $res_pp) : 0;// расчет переменной для вставки в запрос, с какой записи начинать вывод

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
mysqli_query($dbc, 'SET NAMES UTF8');
$query = build_query($user_search, $sort);//Делаем общий запрос без разбивки на страницы
$result = mysqli_query($dbc, $query);
$total = mysqli_num_rows($result);
$num_pages = is_numeric($res_pp) ? ceil($total / $res_pp) : 1;

// Делаем снова запрос с учетом нужной страницы и нужного количества строк
$query = is_numeric($res_pp) ? $query . " LIMIT $skip, $res_pp" : $query;
$result = mysqli_query($dbc, $query);

//Формируем вывод результатов в таблице
echo '<div id="report">'; //Таблица в блоке div для сохранения в CSV	
//Формируем отдельно заголовки таблицы для сохранения в CSV и PDF, $table_header - для CSV  
$table_header = '<table><tr>'.generate_sort_links($user_search, $sort, $h2, $h4, $res_pp).'</tr>';	
$table=' ';	
while ($row = mysqli_fetch_array($result)) {
    $table .= '<tr><td style="text-align:left;"><a target="_blank" href=agr_details.php?rnz='.$row['rnz'].'>'.$row['rnz'].'</a></td>';
    $table .= '<td style="text-align:center;">' . $row['data_reestr'] . '</td>';
	$table .= '<td style="text-align:left;">' . $row['vid_rabot'] . '</td>';
    $table .= '<td style="text-align:left;">' . $row['zakazchik'] . '</td>';
    $table .= '<td>' . $row['summa_rabot'] . '</td></tr>';
    }
$table .= '</table>'; 
echo $table_header.$table;
  
echo '</div>';
//Заголовок таблицы для PDF
$table_pdf = '<table ><tr><th>Номер договору</th><th>Дата договору</th><th>Вид робіт</th><th>Замовник</th><th>Сума робіт</th></tr>';  
  
// Формируем ссылки перехода по страницам
if ($num_pages > 1) {
    echo generate_page_links($user_search, $sort, $cur_page, $num_pages, $h2, $h4, $res_pp);
}
mysqli_close($dbc);

$pdf = $h2.$h4.htmlspecialchars($table_pdf).htmlspecialchars($table);
?>

</br></br>
<a href="#" id ="export" role='button'>Зберегти звіт в CSV</a></br></br>
<a href="#" onClick="document.forms['pdf'].submit()">Зберегти звіт в PDF</a>
<form action="../include/pdf.php" name="pdf" method="post" style="display:none">
	<input name="pdf" type="hidden" value="<?php echo $pdf; ?>">
</form>
</br></br>
<h5>Для збереження звітів повністю виведіть на екран всі рядки звіту.</h5>


<script type="text/javascript" src="../js/save_csv.js" ></script>
<script type="text/javascript">
function resultsPerPage(){
 var res_pp = document.getElementById("res_pp").value;
 var user_search = "<?php echo $user_search; ?>";
 var h2 = "<?php echo $h2; ?>";
 var h4 = "<?php echo $h4; ?>";
 var sort = "<?php echo $sort; ?>";
 location.href = "agr_reestr2.php?usersearch="+user_search+"&h2="+h2+"&h4="+h4+"&sort="+sort+"&res_pp="+res_pp;
}
</script>
</body>
</html>
