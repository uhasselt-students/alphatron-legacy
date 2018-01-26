
<?php
	if (!defined("DB_TYPE")) define("DB_TYPE", "mysql");
	if (!defined("DB_IP")) define("DB_IP", "localhost");
	if (!defined("DB_NAME")) define("DB_NAME", "olivisk117_rpg");
	if (!defined("DB_USERNAME")) define("DB_USERNAME", "olivisk117_rpg");
	if (!defined("DB_PASSWORD")) define("DB_PASSWORD", "jonnyislove1337");


$connection = new PDO(DB_TYPE . ":host=" . DB_IP . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);

?>