<?php
chdir(__DIR__);
require_once('../src/App.php');
require_once(APP_BASE . '/src/helperLibrary.php');
$client = getClient();

try {
    $data = $client->getStudents();
    outputResults('GET Student List', $data);
} catch (Exception $e) {
    $e->showErrors();
}
