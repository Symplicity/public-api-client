<?php
chdir(__DIR__);
require_once('../src/App.php');
require_once(APP_BASE . '/src/helperLibrary.php');
$client = getClient();

$data = getBulkJobData();
try {
    $rez = $client->saveJob($data, ['bulk' => true]);
    outputResults('Bulk POST Jobs', $rez['responses']);
} catch (Exception $e) {
    $e->showErrors();
}
