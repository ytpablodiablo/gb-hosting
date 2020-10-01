<?php

$ServerIP = $_GET['ip'];

header("Location:steam://connect/$ServerIP");
die();

?>