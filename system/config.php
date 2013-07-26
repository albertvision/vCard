<?php

session_start();
error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
date_default_timezone_set('Europe/Sofia');

//Забрраняваме достъп директно до фаила
if (mb_strstr($_SERVER['PHP_SELF'], "config.php", "UTF-8")) {
    die('<h1>Access denied</h1>');
}

// Web site
$system['mysqli']['host']    = 'localhost'; // MySQL сървър
$system['mysqli']['user']    = ''; // MySQL потребител
$system['mysqli']['pass']    = ''; // MySQL парола
$system['mysqli']['name']    = 'portfolio'; // База с данни
$system['mysqli']['charset'] = 'utf8'; // Енкодинг на БД

//Blog 
$system['blog']['url'] = 'http://blog.ygeorgiev.com/'; /* Пълен URL до блога */
$system['blog']['rss'] = 'http://blog.ygeorgiev.com/feed/'; /* Пълен URL до RSS-а на блога*/

//Допълнителни настройки
$system['email'] = 'avbincco@gmail.com'; // Имейл на администратора
$system['debug_mode'] = FALSE;
$system['salt']       = '0cd21bfe67282530f301f9142c57782423eb3c921f98c7c3cb741fcd2e381371'; /* Не пипай! */
$system['pageExt']    = '.html';


?>