<?php 
require_once('../include/startsession.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//UK"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="uk" lang="uk">
<head>
<?php echo "<title>".$title."</title>" ?>
  
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <script type="text/javascript" src="../js/jquery-1.12.4.min.js" ></script>
    <script type="text/javascript" src="../js/date_script.js"></script>
    <script type="text/javascript" src="../js/jquery.chained.remote.min.js"></script>
    <script type="text/javascript" src="../js/jquery.easing-sooper.js"></script>
    <script type="text/javascript" src="../js/jquery.sooperfish.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('ul.sf-menu').sooperfish();
        });
    </script>
</head>

<body>
<div id="main">
    <header>
        <div class="wrapper">
            <span class="date">Monday, June 6, 2011  &nbsp; &nbsp; 17:19</span>
        </div>
        <div id="logo">
            <div id="logo_text">
                <h1><a href="../admin/index.php">Management&nbsp;&nbsp;<span class="logo_colour">Accounting</span></a></h1>
            </div>
        </div>
<?php
if (isset($_SESSION['login'])) {
    echo '</br><h5 class="right">Ви увійшли як '.$_SESSION['fio'].' ('.$_SESSION['login'].')</h5></br></br>
    <a class="right" href="../admin/logout.php">Вихід</a>';
?>
    <nav>
<?php
    switch ($_SESSION['role_id']){
        case 1:
?>
        <ul class="sf-menu" id="nav">
            <li><a href="#">Поточна діяльність</a>
                <ul id="subnav">
                    <li><a href="../potochny/new_agreement.php">Додати договір до реєстру</a></li>
                    <li><a href="../potochny/agreement_plan.php">Планові показники договорів</a></li>
                    <li><a href="../potochny/change_agreement.php">Внести зміни в договір</a></li>
                    <li><a href="../potochny/oplaty.php">Оплати за договорами</a></li>
                    <li><a href="../potochny/zatraty_proekt.php">Витрати за проектами</a></li>
                    <li><a href="../potochny/avans_vidan.php">Видані аванси</a></li>
                </ul>
            </li>
            <li><a href="#">Довідники</a>
                <ul id="subnav">
                    <li><a href="../dovidnik/zakazchik.php">Замовники</a></li>
                    <li><a href="../dovidnik/manager.php">Менеджери</a></li>
                    <li><a href="../dovidnik/vid_rabot.php">Види робіт</a></li>
                    <li><a href="../dovidnik/tip_rabot.php">Типи робіт</a></li>
                    <li><a href="../dovidnik/stan_vykon.php">Стан виконання</a></li>
                    <li><a href="../dovidnik/statya_zatrat.php">Статті витрат</a></li>
                    <li><a href="../dovidnik/sposob_oplaty.php">Способи оплати</a></li>
                    <li><a href="../dovidnik/dzerelo.php">Джерело інформації про компанію</a></li>
                </ul>
            </li>
            <li><a href="#">Звітність</a>
                <ul id="subnav">
                    <li><a href="../zvitnist/pl_period.php">За період в цілому</a></li>
                    <li><a href="../zvitnist/by_managers.php">В розрізі менеджерів</a></li>
                    <li><a href="#">В розрізі клієнтів</a></li>
                    <li><a href="#">За видами діяльності</a></li>
                    <li><a href="../zvitnist/agr_reestr1.php">Реєстр договорів</a></li>
                    <li><a href="../zvitnist/agr_search.php">Пошук договорів за номерами</a></li>
                </ul>
            </li>
            <li><a href="#">Адміністрування</a>
                <ul id="subnav">
                    <li><a href="../admin/user_reg.php">Реєстрація користувача</a></li>
                    <li><a href="../admin/user_del.php">Видалення користувача</a></li>
                </ul>
            </li>
        </ul>
<?php
        break;
        case 2:
?>	
        <ul class="sf-menu" id="nav">
            <li><a href="#">Поточна діяльність</a>
                <ul id="subnav">
                    <li><a href="../potochny/new_agreement.php">Додати договір до реєстру</a></li>
                    <li><a href="../potochny/agreement_plan.php">Планові показники договорів</a></li>
                    <li><a href="../potochny/change_agreement.php">Внести зміни в договір</a></li>
                    <li><a href="../potochny/oplaty.php">Оплати за договорами</a></li>
                    <li><a href="../potochny/zatraty_proekt.php">Витрати за проектами</a></li>
                    <li><a href="../potochny/avans_vidan.php">Видані аванси</a></li>
                </ul>
            </li>
            <li><a href="#">Довідники</a>
                <ul id="subnav">
                    <li><a href="../dovidnik/zakazchik.php">Замовники</a></li>
                    <li><a href="../dovidnik/manager.php">Менеджери</a></li>
                    <li><a href="../dovidnik/vid_rabot.php">Види робіт</a></li>
                    <li><a href="../dovidnik/tip_rabot.php">Типи робіт</a></li>
                    <li><a href="../dovidnik/stan_vykon.php">Стан виконання</a></li>
                    <li><a href="../dovidnik/statya_zatrat.php">Статті витрат</a></li>
                    <li><a href="../dovidnik/sposob_oplaty.php">Способи оплати</a></li>
                    <li><a href="../dovidnik/dzerelo.php">Джерело інформації про компанію</a></li>
                </ul>
            </li>
            <li><a href="#">Звітність</a>
                <ul id="subnav">
                    <li><a href="../zvitnist/pl_period.php">За період в цілому</a></li>
                    <li><a href="../zvitnist/by_managers.php">В розрізі менеджерів</a></li>
                    <li><a href="#">В розрізі клієнтів</a></li>
                    <li><a href="#">За видами діяльності</a></li>
                    <li><a href="../zvitnist/agr_reestr1.php">Реєстр договорів</a></li>
                    <li><a href="../zvitnist/agr_search.php">Пошук договорів за номерами</a></li>
                </ul>
            </li>
        </ul>			
<?php		
        break;
        case 3:
?>			
        <ul class="sf-menu" id="nav">
            <li><a href="#">Поточна діяльність</a>
                <ul id="subnav">
                    <li><a href="../potochny/agreement_plan.php">Планові показники договорів</a></li>
                    <li><a href="../potochny/change_agreement.php">Внести зміни в договір</a></li>
                </ul>
            </li>
            <li><a href="#">Звітність</a>
                <ul id="subnav">
                    <li><a href="../zvitnist/by_managers.php">В розрізі менеджерів</a></li>
                    <li><a href="../zvitnist/agr_search.php">Пошук договорів за номерами</a></li>
                </ul>
            </li>
        </ul>	
<?php		
        break;
    }
?>
	
    </nav>
</header>
<?php
}
else {
    echo '<a href="../admin/login.php">Вхід</a>';
}
echo '<hr />';
?>