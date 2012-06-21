<?php
class Database
{
	public static function connect()
	{
		include("../config/config.inc.php");
		return new mysqli($config["database_host"], $config["database_user_name"], $config["database_user_password"], $config["database_name"]);
	}
}
?>