<?php
chdir(__DIR__);
require_once('../src/App.php');
require_once(APP_BASE . '/src/helperLibrary.php');
$client = getClient();

try {
    $data = $client->getStudent('000835e775e778d5e9b68c57d5c18cab');
    outputResults('GET Student Record', $data);
} catch (Exception $e) {
    $e->showErrors();
}
