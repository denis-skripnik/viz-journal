<?php
require 'functions.php';
$page = homePage();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script><![endif]-->
	<title><?= $page; ?> | Электронный журнал с использованием технологии Блокчейн</title>
	<meta name="keywords" content="Журнал, journal, viz, блокчейн" />
	<meta name="description" content="Электронный журнал с использованием технологии Блокчейн." />
	<link href="style.css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
		<script src="js/sjcl.min.js" type="text/javascript"></script>
		<script src="https://cdn.jsdelivr.net/npm/viz-js-lib@latest/dist/viz.min.js" type="text/javascript"></script>
		<script src="js/helper.js" type="text/javascript"></script>
<style>
/* Eric Meyer's CSS Reset */
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed,
figure, figcaption, footer, header, hgroup,
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure,
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}
/* End of Eric Meyer's CSS Reset */

article, aside, details, figcaption, figure, footer, header, hgroup, main, nav, section, summary {
	display: block;
}
body {
	font: 12px/18px Arial, sans-serif;
}
/* Begin of styles for the demo (you can remove them) */
a.expand {
	width: 90px;
	display: block;
	margin: 10px 0 0;
}
a.expand:hover {
	height: 500px;
}
/* End of of styles for the demo */
.wrapper {
	min-width: 400px;
	max-width: 1920px;
	margin: 0 auto;
}


/* Header
-----------------------------------------------------------------------------*/
.header {
	height: 150px;
	background: #FFE680;
}


/* Middle
-----------------------------------------------------------------------------*/
.middle {
	border-right: 250px solid #FFACAA;
	position: relative;
}
.middle:after {
	display: table;
	clear: both;
	content: '';
}
.container {
	width: 100%;
	float: left;
	overflow: hidden;
	margin-right: -100%;
}
.content {
	padding: 0 20px;
}


/* Right Sidebar
-----------------------------------------------------------------------------*/
.right-sidebar {
	float: right;
	margin-right: -250px;
	width: 250px;
	position: relative;
	background: #FFACAA;
}


/* Footer
-----------------------------------------------------------------------------*/
.footer {
	height: 100px;
	background: #BFF08E;
}
</style>
	</head>

<body>

<div class="wrapper">

	<header class="header">
<h1>Электронный журнал с использованием технологии Блокчейн</h1>
<h2><?= $page; ?></h2>
	</header><!-- .header-->
<nav><ul>
<li><a href="/web">Главная</a></li>
<li><a href="index.php?page=lactors">Преподаватели</a></li>
<li><a href="index.php?page=lessons">Предметы</a></li>
<li><a href="index.php?page=disciples">Ученики</a></li>
<li><a href="index.php?page=lesson_topics">Темы предметов</a></li>
<li><a href="index.php?page=assessments">Оценки учеников</a></li>
<li>Добавление:
<ul><li><a href="add.php?data=lactors">Преподавателя</a></li>
<li><a href="add.php?data=lessons">Предмет</a></li>
<li><a href="add.php?data=disciples">Ученика</a></li>
<li><a href="add.php?data=lesson_topics">Темы предметов</a></li>
<li><a href="add.php?data=assessments">Оценку ученика</a></li></ul></li>
</ul></nav>
	<div class="middle">

		<!-- <a href="#" id="test">tsrt</a> -->

		<div class="container">
			<main class="content">
<h1 class="home-header">Электронный журнал с использованием технологии Блокчейн.
Для отображения данных авторизуйтесь и перейдите на одну из страниц в меню. Если вы уже авторизовались: есть ссылка "выйти", просто выберите пункт меню для начала работы с журналом.</h1>
				
</main><!-- .content -->
		</div><!-- .container-->

		<aside class="right-sidebar">
        <form id="unblock_form">
            <p><label for="viz_login">Введите логин в VIZ: </label></p>
            <p><input type="text" name="viz_login" id="this_login"></p>
            <p><label for="posting">Введите приватный постинг ключ (Начинается с 5). Внимание: он никуда не передаётся, все операции выполняются у вас на компьютере, в вашем браузере.</label></p>
            <p><input type="password" name="posting" id="this_posting"></p>
            <p><input type="checkbox" id="isSavePosting"> Сохранить логин и Постинг ключ</p>
            <p align="center"><input type="button" value="Войти" onclick="userAuth(true)"></p>
        </form>
        <div id="delete_posting_key"></div>
</aside><!-- .right-sidebar -->

	</div><!-- .middle-->

	<footer class="footer">
<p align="center">Создатель сервиса: <a href="https://viz.world/@denis-skripnik" target="_blank">Незрячий программист Денис скрипник</a></p>
	</footer><!-- .footer -->

</div><!-- .wrapper -->
<script>

if (localStorage.getItem('login') && localStorage.getItem('PostingKey')) {
    viz_login = localStorage.getItem('login');
	posting_key = sjcl.decrypt(viz_login + '_postingKey', localStorage.getItem('PostingKey'));
    
    $('#unblock_form').css("display", "none");
	$('#delete_posting_key').css("display", "block");
	
	jQuery("#delete_posting_key").html('<p align="center"><a onclick="localStorage.removeItem(\'login\'\); localStorage.removeItem(\'PostingKey\'\);     location.reload();">Выйти</a></p>');
	
	ajaxSend(viz_login);

} else if (sessionStorage.getItem('login') && sessionStorage.getItem('PostingKey')) {
	
	viz_login = sessionStorage.getItem('login');
	posting_key = sjcl.decrypt(viz_login + '_postingKey', sessionStorage.getItem('PostingKey'));

    $('#unblock_form').css("display", "none");
	$('#delete_posting_key').css("display", "block");
	
	jQuery("#delete_posting_key").html('<p align="center"><a onclick="sessionStorage.removeItem(\'login\'\); sessionStorage.removeItem(\'PostingKey\'\);     location.reload();">Выйти</a></p>');		
	
	ajaxSend(viz_login);

	


} else {
	$('#delete_posting_key').css("display", "none");
	$('#unblock_form').css("display", "block");
}


    </script>
</body>
    </html>