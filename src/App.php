<?php
namespace Symplicity\PublicApiClient;

define('APP_BASE', dirname(__DIR__));
define('APP_PSR4_NAMESPACE', __NAMESPACE__);

require APP_BASE . '/vendor/autoload.php';

class App extends \Slim\App
{
}
