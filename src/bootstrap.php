<?php
if ($_SERVER['DOCUMENT_ROOT']) {
    $base = dirname($_SERVER['DOCUMENT_ROOT']);
} else {
    $base = getcwd();
}
define('APP_BASE', realpath($base));

require_once(APP_BASE . '/local/config.inc');

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 'On');
ini_set('error_log', APP_BASE . '/logs/error_log');
