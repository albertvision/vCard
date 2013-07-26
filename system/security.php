<?php

/**
 * HTML escaping of string
 * @param string $string string to escape
 * @return string Escaped string
 */
function htmlEscape($string) {
    return htmlspecialchars($string);
}

/**
 * Escaping of string
 * @param string $string String to escape
 * @return string escaped string
 */
function escape($string, $type = 'full', $filter = 'filter') {
    if ($filter == 'filter') {
        $string = filter($string);
    } 
    if ($type == 'full') { 
        $escaped = dbEscape(htmlEscape($string));
    } elseif ($type == 'nohtml') { 
        $escaped = dbEscape($string);
    } elseif ($type == 'nomysql') {
        $escaped = addslashes($string);
    }
    return $escaped; 
}

/**
 * XSS Filtering of string
 * @param string $string String to filter
 * @return string Filtered string
 */
function filter($string) { 
    return filter_var($string, FILTER_SANITIZE_STRING);
}

/**
 * String hashing in SHA256
 * @global array $system System array
 * @param string $string String to hashing
 * @param string $salt Hashing salt
 * @return string Hashed string
 */
function encrypt($string, $salt = '') { 
    if($salt == '') {
        global $system;
        $salt = $system['salt'];
    }
    return hash_hmac('SHA256', $string, $salt); 
}

?>