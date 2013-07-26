<?php

global $system; // Взима пътищата
$pages = array( // Масив с основните страниците в контролния панел
    array(
        'title'=>'НАЧАЛО',
        'menu'=>'index.php',
        'sidebar'=>array(
            array(
              'title'=>'Начало',
               'link'=>'index.php'
            ),
            array(
              'title'=>'За системата',
               'link'=>array('index.php','m=about')
            ),
            array(
              'title'=>'Лог с грешки',
               'link'=>array('index.php','m=log')
            ),
        ),
    ),
    array(
        'title'=>'СТРАНИЦИ',
        'menu'=>'pages.php',
        'sidebar'=>array(
            array(
              'title'=>'Списък',
               'link'=>'pages.php'
            ),
            array(
              'title'=>'Нова',
               'link'=>'#'
            ),
        ),
    ),
    array(
        'title'=>'CV',
        'menu'=>'cv.php',
        'sidebar'=>array(
            array(
              'title'=>'Преглед',
               'link'=>'cv.php'
            ),
            array(
                'title'=>'Образование',
                'link'=>array('cv.php','m=edu')
            ),
            array(
                'title'=>'Добави образование',
                'link'=>array('cv.php','add=edu')
            ),
            array(
                'title'=>'Умения',
                'link'=>array('cv.php','m=skills')
            ),
            array(
                'title'=>'Добави умение',
                'link'=>array('cv.php','add=skill')
            )
        ),
    ),
    array(
        'title'=>'ПОТРЕБИТЕЛИ',
        'menu'=>'users.php',
        'sidebar'=>array(
            array(
                'title'=>'Списък',
                'link'=>'users.php'
            ),
            array(
                'title'=>'Добави потребител',
                'link'=>array('users.php','m=add')
            )
        )
    ),
    array(
        'title'=>'СИСТЕМА',
        'menu'=>'system.php',
        'sidebar'=>array(
            array(
              'title'=>'Настройки',
               'link'=>'system.php'
            ),
            array(
              'title'=>'Лог с грешки',
               'link'=>array('system.php','m=log')
            ),
        ),
    ),
    array(
        'title'=>'КЪМ САЙТА',
        'menu'=>'../')
    );

$currentPage = str_replace($system['paths']['adminUrl'], '', $_SERVER['SCRIPT_NAME']); // Текуща страница
//$fullCurrentPage = str_replace($system['paths']['adminUrl'], '', $_SERVER['REQUEST_URI']); // Параметри
$fullCurrentPage = $currentPage;
if(strlen($_SERVER['QUERY_STRING'])) {
    $fullCurrentPage = $currentPage.'?'.$_SERVER['QUERY_STRING'];
}
$cg = explode('?',$fullCurrentPage);
if(count($cg)>1) {
    $countGet = explode('&',$cg[1]);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8" />
        <title><?= $title; ?> | Контролен панел - ДМШ "Свиленград"</title>
        <!-- CSS -->
        <link href="css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="css/jquery.wysiwyg.css" rel="stylesheet" type="text/css" media="screen" />
        <!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="css/ie6.css" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="css/ie7.css" /><![endif]-->
        <!-- JavaScripts-->
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jNice.js"></script>
        <script type="text/javascript" src="js/jquery.wysiwyg.js"></script>
    </head>
    <body>
        <div id="wrapper">
            <h1><a href="index.php"><span>Администраторски панел</span></a></h1><!-- Лого -->
            <!-- Main navigation -->
            <ul id="mainNav">
                <?php
                foreach($pages as $value){
                    ?><li><a href="<?= $value['menu']?>" <?php if('/'.$value['menu']==$currentPage || '/'.$value['second']==$currentPage || $_GET['']) { $item = ucfirst($value['title']); echo 'class="active"'; } ?>><?= $value['title']; ?></a></li><?php
                }
                ?>
                <li class="logout"><a href="logout.php">ИЗХОД</a></li>
            </ul> <!-- End of main navigation -->
            <!-- Content box -->
            <div id="containerHolder">
                <div id="container">
                    <!-- Right sidebar -->
                    <div id="sidebar">
                        <ul class="sideNav">
                            <?php
                            foreach($pages as $key=>$value) { // Обхожда страниците
                                if('/'.$value['menu']==$currentPage || '/'.$value['second']==$currentPage) {
                                    foreach($value['sidebar'] as $page) {
                                        $i++;
                                        /*if($fullCurrentPage == '/'.$page['link'] || ($fullCurrentPage == '/'.$page['link'] && mb_strstr($_SERVER['QUERY_STRING'], 'page='))) {
                                            echo 'curent';
                                        }*/
                                        if(is_array($page['link'])) {
                                            if($currentPage == '/'.$page['link'][0] && mb_strstr($_SERVER['QUERY_STRING'], $page['link'][1])) {
                                                $class[$i] = 'class="active"';
                                                $child = $page['title'];
                                            }
                                            $page['link'] = $page['link'][0].'?'.$page['link'][1];
                                        } else {
                                            $p[$i] = explode('?',$page['link']);
                                            if(count($p[$i])>1) {
                                                if($currentPage == '/'.$p[$i][0] && mb_strstr($_SERVER['QUERY_STRING'], $p[$i][1])) {
                                                    $class[$i] = 'class="active"';
                                                    $child = $page['title'];
                                                }
                                            } elseif($fullCurrentPage == '/'.$page['link'] || ($currentPage == '/'.$page['link'] && $_GET['page'] && count($p) == count($countGet))) {
                                                $class[$i] = 'class="active"';
                                                $child = $page['title'];
                                            }
                                        }
                                        ?><li><a href="<?= $page['link']; ?>" <?php echo $class[$i]; ?>><?= $page['title']?></a></li><?php
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div><!-- End right sidebar-->
                    <h2><a href="<?php if($currentPage == '/photos.php') { echo 'galleries.php'; } else { echo '.'.$currentPage; }  ?>"><?= $item ?></a> <?php if(!is_null($child)) { ?>&raquo; <a href=".<?= $fullCurrentPage; ?>" class="active"><?= $child; ?></a><?php } ?></h2> <!-- Заглавие -->
                    <div id="main"> <!-- Main content -->