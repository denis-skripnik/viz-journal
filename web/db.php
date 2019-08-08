<?php
function dbConnect() {
// $json_conf = require_once $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR . 'config.json';
$json_conf = file_get_contents( '../config.json' );
$config = json_decode($json_conf, JSON_OBJECT_AS_ARRAY);

//название базы данных
$dbname = $config['db_name'];
//имя пользователя
$username = $config['db_login'];
//пароль
$pass = $config['db_password'];
//подключаемся к базе данных



$db = new PDO(
    "mysql:host=".$config['db_server'].";dbname={$dbname};charset=utf8",
    $username,
    $pass
);
return $db;
}
?>