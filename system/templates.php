<?php

// Header
function siteTop($title, $displayTitle = true, $pageId = false) {
    global $system;
    require_once $system['paths']['systemPath'].'/templates/SiteTop.php';
}

function siteFooter() { //Footer
    global $system;
    require_once $system['paths']['systemPath'].'/templates/SiteFooter.php';
}

// Позиции за контролния панел
function adminTop($title, $child = '') {
    global $system;
    require_once $system['paths']['systemPath'] . '/templates/AdminHead.php';
}

function adminFooter() {
    global $system;
    require_once $system['paths']['systemPath'] . '/templates/AdminFooter.php';
}

function title($string, $class=false) {
    if($class!=false) {
        echo '<div class="'.$class.'"><h2>'.$string.'</h2></div>';
    } else {
        echo '<h2>'.$string.'</h2><div class="hr"></div>';
    }
}

//Messages
function error($string) {
    return '<p class="error">'.$string.'</p>';
}
function success($string) {
    return '<p class="success">'.$string.'</p>';
}
function warning($string) {
    return '<p class"warniing">'.$string.'</p>';
}

function showMessages($errorObj = 'error', $successObj = 'success') {
    global $$errorObj, $$successObj;
    $error = $$errorObj;
    $success = $$successObj;
    
    if(!is_null($error)) {
        if(is_array($error)) {
            foreach($error as $value) {
                echo error($value);
            }
        } else {
            echo error($error);
        }
    }
    if(!is_null($success)) {
        if(is_array($success)) {
            foreach($success as $value) {
                echo success($value, $location);
            }
        } else {
            echo success($success, $location);
        }
    }
}
?>