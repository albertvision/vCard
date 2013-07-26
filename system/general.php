<?php

/**
 * 
 * @global array $system System array
 * @param string $name Variable to search
 */
function getSystemVar($name) {
    global $system;
    return $system[$name];
}

function getSettings() {
    return dbAssoc(dbQuery('SELECT * FROM `settings`'));
}
/**
 * Generates random string
 * @param int $length Length of string
 * @return string Generated string
 */
function gstring($length) { //Generates string
    $random = str_shuffle('0123456789qwertyuiopasdfghjklzxcvbnm');
    $cut = substr($random, 0, $length);
    return $cut;
}

/**
 * Add form input
 * @param string $type Input type
 * @param string $name Input name
 * @param string $value Input value
 * @param string $id Input CSS ID
 * @param array $error Error array
 * @param string $class Input CSS class
 * @return string
 */
function addInput($type, $name, $value = '', $id = '', $error = '', $class = 'text-input') {
    if(is_array($error) && array_key_exists($name, $error)) {
        $messages = '<span class="validate-plugin no-float">'.$error[$name].'</span>';
    }
    return '<input type="'.$type.'" name="'.$name.'" value="'.htmlspecialchars($value).'" id="'.$id.'", class="'.$class.'" /> '.$messages;
}

/**
 * Send email
 * @param type $receiverEmail Receiver email
 * @param type $senderEmail Sender email
 * @param type $senderName Sender name
 * @param type $subject Email subject
 * @param type $content Email content
 * @return boolean
 */
function sendMail($receiverEmail, $senderEmail, $senderName, $subject, $content) {
    $headers='MIME-Version: 1.0'."\r\n";
    $headers.='Content-type: text/html; charset=UTF-8'."\r\n";
    $headers.='From: '.iconv("UTF-8", "windows-1251", $senderName).' <'.$senderEmail.'>'."\r\n";
    
    if(mail($receiverEmail, iconv("UTF-8", "windows-1251", $subject), stripslashes($content), $headers)) { 
        return true;
    } else {
        return false;
    }
}
/**
 * Transliterating of string
 * @param string $text String to transliterate
 * @return string Transliterated string
 */
function transliterate($text) {
    $en = array("a", "b", "v", "g", "d", "e", "zh", "z", "i", "i", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "ts", "ch", "sh", "sht", "u", "io", "iu", "q", "a", "b", "v", "g", "d", "e", "zh", "z", "i", "ii", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "ch", "sh", "sht", "u", "io", "iu", "q", "ch", "sh", "sht", "_", "", "", "", "", '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''); //латинските букви
    $bg = array("а", "б", "в", "г", "д", "е", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ь", "ю", "я", "А", "Б", "В", "Г", "Д", "Е", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ь", "Ю", "Я", "ч", "ш", "щ", " ", ",", ".", "&", "'", '"', "!", "?", "(", "[", "]", ")", ";", ":", "-", "”", "„", "+", "“", "/", '"', "'"); //кирилица, съответстващ на латиницата по-горе
    $transform = str_replace($bg, $en, $text);
    $ready = str_replace("__", "_", $transform);
    return $ready;
}
/**
 * Search word in string
 * @param string $string String in which to search
 * @param string $what Word to search
 * @return boolean
 */
function search_string($string, $what) { 
    $pos = strpos($string, $what);
    if ($pos !== false) {
        return true;
    } else {
        return false;
    }
}
/**
 * Redirect
 * @param string $link Link to redirect
 */
function redirect($link) {
    header("Location: $link");
    die();
}

/**
 * Verify that the user has logged.
 * @return boolean
 */
function isLogged() {
    if (!$_SESSION['logged']) { // Ако няма сесия
        if ($_COOKIE['loginKey']) { // Ако има бисквитка, която да стартира сесия
            $query = dbQuery('SELECT * FROM `users` WHERE `loginKey`="' . $_COOKIE['loginKey'] . '"');
            if (dbCount($query)) {
                $user = dbAssoc($query);
                $_SESSION['logged'] = TRUE; // Стартира сесия
                $_SESSION['user'] = $user; // Взема данните на потребителя
            }
        }
    } 
    if($_SESSION['logged'] && $_SESSION['user']) {
        return true;
    } else {
        return false;
    }
}
/**
 * Redirect if user is logged
 * @param string $page Page to redirect
 */
function redirectIfLogged($page = 'index.php') {
    if(isLogged()) {
        redirect($page);
    }
}

/**
 * Redirect if user isn't logged
 * @param string $page Page to redirect
 */
function redirectIfGuest($page = 'login.php') {
    if(!isLogged()) {
        redirect($page);
    }
}


/**
 * Translates date format to bulgarian months
 * @param int $timestamp Timestamp
 * @return string Translated date format
 */
function myDate($timestamp, $show = 'all') {
    $months = array("","Януари", "Февруари", "Март", "Април", "Май", "Юни", "Юли", "Август", "Септември", "Октомври", "Ноември", "Декември");
    $m = date('n',$timestamp);
    $month = str_replace($m,$months[$m],$m);
    if($show == 'all') {
        $format = date('d '.$month.' Y в H:i:s',$timestamp);
    } elseif($show == 'short') {
        $format = date('d.'.$m.'.Y в H:i:s',$timestamp);
    }
    return $format;
}

/**
 * Resizes images
 * @param string $tmpName Tmp file
 * @param string  $fileType File type
 * @param string $uploadPath Upload path
 * @param int $newWidth New width
 * @param int $newHeight New height
 * @return boolean
 */
function resizeImage($tmpName, $fileType, $uploadPath, $newWidth = 200, $newHeight = 200) { 
    $fileType = strtolower($fileType);
    if ($fileType == "jpg" || $fileType == "jpeg" || $fileType == "gif" || $fileType == "png") { 
        list($imageWidth, $imageHeight) = getimagesize($tmpName); 
        $width = $newWidth; 
        $height = $newHeight; 
        if ($imageWidth < $width && $imageHeight < $height) { 
            move_uploaded_file($tmpName, $uploadPath); 
        } elseif ($imageWidth >= $width) { 
            $newWidth = $width; 
            $newHeight = (int) ($imageHeight * $newWidth) / $imageWidth; 
        } elseif ($imageHeight >= $height) {  
            $newHeight = $height; 
            $newWidth = (int) ($imageWidth * $newHeight) / $imageHeight;
        }

        $imagecreatefrom = 'imagecreatefrom';
        if ($fileType == "jpeg" || $fileType == "jpg") {
            $imagecreatefrom .= 'jpeg';
        } else {
            $imagecreatefrom .= $fileType;
        }

        $imageO = imagecreatetruecolor($newWidth, $newHeight);
        $imageT = $imagecreatefrom($tmpName);
        imagecopyresampled($imageO, $imageT, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageHeight);
        imagejpeg($imageO, $uploadPath, 100);
        return true;
    } else {
        return false;
    }
}

/**
 * Gets to the desired size
 * @param string $image Image path
 * @param int $width Width to resize
 * @param int $height Height to resize
 * @return array
 */
function getNewImageSize($image, $width, $height) {
    list($imageWidth, $imageHeight) = getimagesize($image);

    if ($imageWidth < $width && $imageHeight < $height) {
        $newWidth = $width;
        $newHeight = $height;
    } elseif ($imageWidth >= $width) {
        $newWidth = $width; 
        $newHeight = (int) ($imageHeight * $newWidth) / $imageWidth; 
    } elseif ($imageHeight >= $height) {  
        $newHeight = $height;
        $newWidth = (int) ($imageWidth * $newHeight) / $imageHeight; 
    }

    return array($newWidth, $newHeight);
}

/**
 * Upload a file
 * @param string $tmpName File Tmp
 * @param string $path Path to upload
 * @return boolean
 */
function uploadFile($tmpName, $path) {
    if (move_uploaded_file($tmpName, $path)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if domain is valid
 * @param string $domain Domain to check
 * @return boolean | string
 */
function checkDomain($domain)
{
    if (preg_match('/(^(https?):\/\/|^)(([^\.]+)\.([^\.]+\.[^\/$]+)|([^\.]+\.[^\/$]+))\/?$/', $domain, $matches)) {
        return $matches[5] ? $matches[5] : $matches[6];
    } else {
        return false;
    }
}

/**
 * Dumps of array
 * @param array $variable Array to dumping
 * @return void
 */
function dump_var($array) {
    echo '<pre>'.print_r($array, true).'</pre>';
}

/**
 * Save log in db
 * @param string $msg Message to log
 */
function errorLog($msg,$type = '') {
    global $system;
    $debugTrace = debug_backtrace();
    file_put_contents($system['paths']['systemPath'].'/log.out', date('d M Y H:i:s'). ' [' . ($type!='' ? strtoupper($type) : 'NO TYPE') ."] [$_SERVER[SCRIPT_NAME]] [Line: {$debugTrace[0][line]}] [$_SERVER[REMOTE_ADDR]] >> $msg \n", FILE_APPEND);
    dbQuery('INSERT INTO `logs` VALUES(NULL,"'.escape($msg).'","'.  strtoupper($type).'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['SCRIPT_NAME'].'",'.$debugTrace[0]['line'].','.time().')');
}

/**
 * $currentPage = $_GET['p']
 * 
 * @return array
 * @param string $currentPage GET parameter to current page
 * @param int $getNumRowsQuery MySQL query to get count of the results in table
 * @param string $getPagePath URL path
 * @param int $elementsPerPage Default: 5
 * @param string $ext Link extension
 */
function pagination($currentPage, $getNumRowsQuery, $getPagePath, $elementsPerPage = 5, $ext = '.html') {
    global $system;
    $elementsPerPage = (int) $elementsPerPage;
    $query = dbQuery($getNumRowsQuery);
    $getNumRows = dbCount($query);
    $totalPages = $getNumRows / $elementsPerPage;
    $intvalPages = intval($totalPages);
    $lastPage = ceil($getNumRows / $elementsPerPage);
    $currentPage = (int) $currentPage;
    $getPagePath = $system['paths']['siteUrl'] . '/' . $getPagePath;

    if ($totalPages - $intvalPages > 0) {
        $totalPages = $intvalPages + 1;
    }

    if ($currentPage <= 1 || $currentPage > $totalPages) {
        $start = 0;
        $end = $elementsPerPage;
        $currentPage = "1";
    } else {
        $start = $elementsPerPage * ($currentPage - 1);
        $end = $currentPage * $elementsPerPage;
    }
    if ($lastPage > 1) {
        if ($currentPage > 1) {
            $pagesNum .= '<a href="' . $getPagePath . ($currentPage - 1) . $ext . '">&laquo; Назад</a> ';
        }
        if ($lastPage <= 10) {
            $pagesNum .= ' | ';
            for ($i = 1; $i <= $lastPage; $i++) {
                $pagesNum .= '<a href="' . $getPagePath . $i . $ext . '">' . $i . '</a> | ';
            }
        } else {
            if ($currentPage <= 5) {
                for ($i = 1; $i <= 10; $i++) {
                    $pagesNum .= ' | <a href="' . $getPagePath . $i . $ext . '">' . $i . '</a> ';
                }
                $pagesNum .= ' | ... | <a href="' . $getPagePath . $lastPage . $ext . '">' . $lastPage . '</a> | ';
            } elseif ($currentPage > 5 && $currentPage < ($lastPage - 5)) {
                $pagesNum .= ' | <a href="' . $getPagePath . '1.'. $ext . '">1</a> | ... | ';
                for ($i = $currentPage - 3; $i <= $currentPage + 3; $i++) {
                    $pagesNum .= '<a href="' . $getPagePath . $i . $ext . '">' . $i . '</a> | ';
                }
                $pagesNum .= ' ... | <a href="' . $getPagePath . $lastPage . '">' . $lastPage . '</a> | ';
            } elseif ($currentPage >= ($lastPage - 5)) {
                $pagesNum .= ' | <a href="' . $getPagePath . '1.' . $ext . '">1</a> | ... | ';
                if ($currentPage < ($lastPage - 2)) {
                    for ($i = $currentPage - 3; $i <= $currentPage + 3; $i++) {
                        $pagesNum .= '<a href="' . $getPagePath . $i . $ext . '">' . $i . '</a> | ';
                    }
                } else {
                    for ($i = $lastPage - 5; $i <= $lastPage; $i++) {
                        $pagesNum .= '<a href="' . $getPagePath . $i . $ext . '">' . $i . '</a> | ';
                    }
                }
            }
        }
        if ($currentPage < $lastPage) {
            $pagesNum .= ' <a href="' . $getPagePath . ($currentPage + 1) . $ext . '">Следваща &raquo;</a>';
        }
        $pagination = str_replace('<a href="' . $getPagePath . $currentPage . $ext . '">' . $currentPage . '</a>', '<b>' . $currentPage . '</b>', $pagesNum);
    }
    return array(
        'show' => $pagination,
        'elementsPerPage' => $elementsPerPage,
        'start' => $start
    );
}

function returnLevel($glevel) {
    switch ($glevel) {
        case '1':
            $level = 'Начално';
            break;
        case '2':
            $level = 'Основно';
            break;
        case '3':
            $level = 'Средно';
            break;
        case '4':
            $level = 'Средно специално';
            break;
        case '5':
            $level = 'Професионален бакалавър';
            break;
        case '6':
            $level = 'Бакалавър';
            break;
        case '7':
            $level = 'Магистър';
            break;
        case '8':
            $level = 'Доктор';
            break;
        default:
            $level = 'Неизвестен';
            break;
    }
    
    return $level;
}
?>