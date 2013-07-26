<?php

require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/general.php';
require_once dirname(__FILE__).'/database.php';
require_once dirname(__FILE__).'/security.php';
require_once dirname(__FILE__).'/templates.php';

dbConnect($system['mysqli']['host'], $system['mysqli']['user'], $system['mysqli']['pass'], $system['mysqli']['name']);
?>