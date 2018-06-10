<?php
chdir(__DIR__);
require_once('../src/App.php');
require_once(APP_BASE . '/src/helperLibrary.php');
$client = getClient();

$data = getJobData();
try {
    $rez = $client->saveJob($data);
    outputResults('save Job', $rez);
} catch (Exception $e) {
    $e->showErrors();
}
