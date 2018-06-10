<?php
chdir(__DIR__);
require_once('../src/App.php');
require_once(APP_BASE . '/src/helperLibrary.php');
$client = getClient();

$data = getJobData([
    'postingDate' => '2017-09-12',
    'created' => '2017-09-28 00:45:54',
    'approval_date' => '2017-09-28 00:45:54',
    'expirationDate' => '2018-09-12'
]);
try {
    $rez = $client->saveJob($data);
    outputResults('save Job with Validation Errors', $rez);
} catch (Exception $e) {
    $e->showErrors();
}
