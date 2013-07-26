<?php

$system['paths']['sitePath']   = str_replace('\\','/',realpath(__DIR__));
$system['paths']['systemPath'] = realpath(__DIR__).'/system'; // Full or short
$system['paths']['siteUrl']    = str_replace($_SERVER['DOCUMENT_ROOT'], '', $system['paths']['sitePath'].'/');
$system['paths']['adminUrl']   = $system['paths']['siteUrl'].'/admin';

require_once $system['paths']['systemPath'].'/system.php';
?>