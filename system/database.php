<?php

/**
 * MySQLi Connect
 * @param string $dbhost MySQLi Host
 * @param string $dbuser MySQLi Username
 * @param string $dbname Database Name
 */
function dbConnect($dbhost, $dbuser, $dbpass, $dbname, $conName = 'mysqli') {
    global $system;
    $mysqli = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if(mysqli_connect_error()) {
        die('MySQLi Connection Failed!');
    }
    dbSetCharset($system[$conName]['charset'], $mysqli);
    $system['mysqli']['connection'] = $mysqli;
}

/**
 * MySQLi Connect to DNS db
 */
function dbConnectDns() {
    global $system;
    $mysqli_db = mysqli_connect($system['dns']['host'], $system['dns']['user'] , $system['dns']['pass'] , 'dnsmanage_pdns');
    if(mysqli_connect_error()) {
        die('MySQLi Connection Failed!');
    }
    dbSetCharset($system['mysqli']['charset'], $mysqli_db);
    $system['mysqli']['connection'] = $mysqli_db;
}

/**
 * Set MySQLi Charset
 * @param string $charset MySQLi charset to set
 * @param array $con MySQLi Connection Link
 */
function dbSetCharset($charset, $con) {
    if (!mysqli_set_charset($con, $charset)) {
        die(printf('MySQLi set charset to <u>%s</u> failed!', $charset));
    }
}

/**
 * Execute MySQLi Query
 * @global array $system System data
 * @param string $query MySQLi Query
 */
function dbQuery($query) {
    global $system;
    $con = $system['mysqli']['connection'];
    $debugMode = $system['debug_mode'];
    $query = mysqli_query($con,$query);
    
    if(!$query) {
        if ($debugMode == TRUE) { 
            errorLog(mysqli_error($con),'mysqli'); 
            echo mysqli_error($con);
            die(); 
        } else { 
            errorLog(mysqli_error($con),'mysqli'); 
            return false;
        }
    } else {
        return $query;
    }
}

function dbError() {
    global $system;
    return mysqli_error($system['mysqli']['connection']);
}
/** 
 * @param type $query Link to executed MySQLi Query
 * @return array Returned data in array
 */
function dbAssoc($query) { 
    return mysqli_fetch_assoc($query);
}

/**
 * Get count of table's results
 * @param type $query Link to executed MySQLi Query
 * @return int Count of results in table
 */
function dbCount($query) {
    return mysqli_num_rows($query);
}

/**
 * MySQLi Special Escape
 * @global array $system
 * @param string $string String to escape
 * @return string Escaped string
 */
function dbEscape($string) {
    global $system;
    return mysqli_real_escape_string($system['mysqli']['connection'], $string);
}

/**
 * Check is prepary statemant ready
 * @global array $system System array
 * @param string $query Query string. NOT EXECUTED QUERY
 * @return boolean|object
 */
function dbPrepare($query) {
    global $system;
    $stmt = mysqli_prepare($system['mysqli']['connection'],$query);
    return $stmt;
}

/**
 * MySQLi dumping
 * @param string $tables * to all
 * @return string|boolean
 */
function dbDump($full='',$tables='*') {
    global $system;
    if ($tables == '*') {
        $tables = array();
        $result = dbQuery('SHOW TABLES');
        while ($row = dbCount($result)) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    $return.= '-- Created by Yasen Georgiev Backup Generator'."\n-- Generated on ".date('d M Y H:i:s')."\n\n";
    //cycle through
    foreach ($tables as $table) {
        $result = dbQuery('SELECT * FROM ' . $table);
        $num_fields = mysqli_num_fields($result);

        $row2 = dbCount(dbQuery('SHOW CREATE TABLE ' . $table));
        $return.= $row2[1] . ";\n\n";
        $return.= 'DROP TABLE ' . $table . ';'."\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = dbCount($result)) {
                $return.= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $return.= '"' . $row[$j] . '"';
                    } else {
                        $return.= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return.= ',';
                    }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n";
    }

    //save file
    if($full=='full') {
        $file = $system['paths']['sitePath'].'/db.sql';
    } else {
        $file = $system['paths']['systemPath'].'/backups/mysql-' . time() . '-' . (md5(implode(',', $tables))).'.sql';
    }
    $handle = fopen('..'.$file, 'w+');
    if($full=='full') {
        fwrite($handle, $return);
        fclose($handle);
        return $file;
    } else {
        dbQuery('INSERT INTO `backups` VALUES (NULL, "'.$file.'",1,'.time().')');
        if(mysql_error()) {
            return false;
        } else {
            fwrite($handle, $return);
            fclose($handle);
            return true;
        }
    }
}
?>