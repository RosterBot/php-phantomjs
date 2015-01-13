<?php

require '../vendor/autoload.php';

use JonnyW\PhantomJSBundle\Client;

$client = Client::getInstance();
$client->setPhantomJs('/path/to/phantomjs'); // PhantomJS executbale file path
