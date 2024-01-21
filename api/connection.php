<?php

require_once("consts.php");

$connection = new mysqli(DBHOST, DBUSER, DBPASS, DBTABLE);
$connection->set_charset("utf8mb4");
