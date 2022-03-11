<?php


use Src\Router;


require_once (__DIR__ . '/../vendor/autoload.php');
$con = new PDO("mysql:host=127.0.0.1;dbname=test", "root");
var_dump($con);die;

define ('ROOT_FOLDER', dirname(__DIR__));
if ($con = new PDO("mysql:host=localhost :3306;dbname=intention", "root", "" )) {
    echo 111111111111;
} else {
    echo 000000000000000;
}
die('here');
//$con = new PDO("mysql:host=localhost;dbname=intention", "root", "" ); die('yes');
$app = new Router();die;

