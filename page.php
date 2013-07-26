<?php

include 'load.php';
if($_GET['link']) {
    $ajax = $_GET['ajax'];
    $link = escape($_GET['link']);
    $query = dbQuery('SELECT `title`,`content` FROM `pages` WHERE `key`="'.$link.'"');
    if($count = dbCount($query)) {
        $page = dbAssoc($query);
        siteTop($page['title']);
        echo $page['content'];
    } else {
        siteTop('Грешка 404');
        ?><p>Страницата, която търсите, изглежда, че не съществува! Най-вероятно е изтрита или никога не е съществувала!</p><?php
    }
} else {
    $query = dbQuery('SELECT `title`,`content` FROM `pages` WHERE `isHome`=1');
    if(!dbCount($query)) {
        siteTop('Начало');
        ?><p>Няма зададена начална страница!</p><?php
    } else {
        $page = dbAssoc($query);
        siteTop($page['title']);
        echo $page['content'];
    }
}
siteFooter();
?>