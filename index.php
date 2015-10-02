<?php
require 'vendor/autoload.php'
use RedBean_Facade as R;

R::setup("mysql:host=localhost;dbname=redBeanTest;charset=utf8", "root", "root");
R::freeze(true);

$app = new \Slim\Slim(array(
	'debug' => true
	));

require 'api/apis.php'

require 'functions/function.php'

$app->run();




