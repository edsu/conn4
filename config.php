<?php

session_start();

# you may want the database to live somewhere more...permanent
# but just make sure wherever it is it is writeable by user  www-data

$GLOBALS['DB'] = new PDO("sqlite:/var/tmp/conn4.db");
$GLOBALS['USER_ID'] = session_id();

?>
