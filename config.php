<?php

session_start();

# you may want the database to live somewhere more...permanent

$GLOBALS['DB'] = new PDO("sqlite:/var/tmp/conn4.db");
$GLOBALS['USER_ID'] = session_id();

?>
