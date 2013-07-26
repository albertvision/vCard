<?php

$settings = getSettings();
$pages_query = dbQuery('SELECT `title`, `key` FROM `pages`');
$nav['dynamic'] = array();
while($page = dbAssoc($pages_query)) {
    $class = 'blog';
    if($page['key'] == 'za_men') {
        $class = 'about';
    }
    array_push(
        $nav['dynamic'], array(
            $page['title']=>array(
                'link'=>"page/$page[key].html",
                'class'=>$class
            )
        )
    );
}

$nav['static'] = array(
    'Автобиография'=>array(
        'link'=>'cv.html',
        'class'=>'resume'
    ),
    'Блог'=>array(
        'link'=>'blog.html',
        'class'=>'blog'
    ),
    'Контакти'=>array(
        'link'=>'contacts.html',
        'class'=>'contact'
    )
);

?>
<!DOCTYPE html> 
<html>

    <head>
        <title><?= $title; ?> | <?= $settings['title']; ?></title>
        <meta charset="UTF-8" />
        <meta name="description" content="<?= htmlEscape($settings['desc']); ?>" />
        <meta name="keywords" content="<?= htmlEscape($settings['keywords']); ?>" />
        
        <!-- Styles -->
        <link rel='stylesheet' href='<?= $system['paths']['siteUrl']; ?>/css/style.css' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= $system['paths']['siteUrl']; ?>/css/museo.css' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= $system['paths']['siteUrl']; ?>/css/default.css' type='text/css' media='all' />
        <link rel='stylesheet' href='<?= $system['paths']['siteUrl']; ?>/css/colorbox.css' type='text/css' />
        <link rel="stylesheet" href="<?= $system['paths']['siteUrl']; ?>/css/validationEngine.jquery.css" type="text/css"/>
        <link rel='stylesheet' href='<?= $system['paths']['siteUrl']; ?>/css/tipsy.css' type='text/css' />

        <!-- Scripts -->
        <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>

        <script type='text/javascript' src='<?= $system['paths']['siteUrl']; ?>/js/jquery.mousewheel.js'></script>
        <script type="text/javascript" src="<?= $system['paths']['siteUrl']; ?>/js/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="<?= $system['paths']['siteUrl']; ?>/js/jquery.tipsy.js"></script>
        
        <script type="text/javascript" src="<?= $system['paths']['siteUrl']; ?>/js/jquery.validationEngine-bg.js"></script>
	<script type="text/javascript" src="<?= $system['paths']['siteUrl']; ?>/js/jquery.validationEngine.js"></script>
        
        <script type='text/javascript' src='<?= $system['paths']['siteUrl']; ?>/js/custom.js'></script>


    </head>
    <body>
        <!--<div><img id="img-source" src="<?= $system['paths']['siteUrl']; ?>/images/background-body.png" alt="" /></div>--><div id="wr-layout" class="expanded">
            <div id="layout" class="clearfix">
                <div id="sidebar">
                    <ul>
                        <?php foreach ($nav['dynamic'] as $id=>$cont) { 
                            foreach($cont as $p_title=>$additional) { ?>
                            <li class="<?php if($_SERVER['REQUEST_URI'] == $system['paths']['siteUrl'].'/'.$additional['link']) { echo ' active'; }?>"><a href="<?= $system['paths']['siteUrl'].'/'.$additional['link']; ?>" class="<?= $additional['class']; ?>"><?= $p_title; ?></a><span></span></li>
                        <?php } } ?>
                        <?php foreach ($nav['static'] as $p_title=>$additional) { ?>
                            <li class="<?php if($_SERVER['REQUEST_URI'] == $system['paths']['siteUrl'].'/'.$additional['link']) { echo ' active'; }?>"><a href="<?= $system['paths']['siteUrl'].'/'.$additional['link']; ?>" class="<?= $additional['class']; ?>"><?= $p_title; ?></a><span></span></li>
                        <?php } ?>
                    </ul>	
                </div>
                <div id="content">
                    <div id="user" class="clearfix">
                        <div class="profile-info">
                            <h1>Ясен Георгиев</h1>
                            <div class="wp-pi clearfix">
                                <ul id="social" class="clearfix">
                                    <li><a href="http://facebook.com/<?= $settings['facebook']; ?>" class="facebook"></a></li>
                                    <li><a href="http://twitter.com/<?= $settings['twitter']; ?>" class="twitter"></a></li>
                                    <li><a href="contacts.html" class="location"></a></li>
                                </ul>
                                <div><span>Уеб разработчик</span></div>
                            </div>
                        </div>
                        <div class="profile-image"></div>
                    </div>
                    <div id="pages">
                        <div <?php if($pageId != false) { echo 'id="page-'.$pageId.'"'; } ?> class="page-wrapper active" >
                            <div class="content scroll-pane">
                                <?php if($displayTitle == true) { ?><h2 class="h-bor-bot mt"><?= $title; ?></h2><?php } ?>
                                <div <?php if($pageId != false) { echo 'id="wrapper-'.$pageId.'"'; } ?>>
                                        