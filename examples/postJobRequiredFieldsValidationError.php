<?php
chdir(__DIR__);
require_once('../src/App.php');
require_once(APP_BASE . '/src/helperLibrary.php');
$client = getClient();

$data = getJobData([
    'title' => null,
    'annuallyRecurring' => null
]);
unset($data['locations'][0]['state']);
try {
    $rez = $client->saveJob($data);
    outputResults('save Job with required fields Validation Errors', $rez);
} catch (Exception $e) {
    $e->showErrors();
}
